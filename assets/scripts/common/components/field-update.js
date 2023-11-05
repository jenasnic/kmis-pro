import debounce from 'lodash/debounce';

import { bindForm } from '../form';

/**
 * Allows to update one or more elements depending on another one using Symfony form validation.
 * Use 'data-fields-update' property with identifier of html wrapper to add form element.
 * NOTE: Implement Symfony using FormEvents to set data...
 * NOTE 2: DataReferer can be used in case of collection...
 *
 * Sample :
 *  <div id="{{ form.vars.id }}">
 *      {{ form_row(form.addMoreInfos, {'attr': {'data-field-update': 'fieldsWrapper'}, 'data-field-update-container': form.vars.id}) }}
 *
 *      <div data-field-update-target="fieldsWrapper" id="fields-wrapper-1">
 *          {{ form_row(form.additionalInfos1) }}
 *      </div>
 *
 *      <div data-field-update-target="fieldsWrapper" id="fields-wrapper-2">
 *          {{ form_row(form.additionalInfos2) }}
 *      </div>
 *  </div>
 *
 * @param input
 */
export const bindFormFieldUpdate = (input) => {
  if ('text' === input.type) {
    input.addEventListener('input', debounce(({ target, key }) => {
      return refreshData(input);
    }, 300));
  } else {
    input.addEventListener('change', () => refreshData(input));
  }
};

const refreshData = async (element) => {
  const fieldUpdate = element.dataset.fieldUpdate;
  if (!fieldUpdate) {
    return;
  }

  const container = element.dataset.fieldUpdateContainer && document.getElementById(element.dataset.fieldUpdateContainer);

  const wrappers = (container || document).querySelectorAll(`[data-field-update-target="${fieldUpdate}"]`);
  if (!wrappers) {
    return;
  }

  const formToSubmit = element.closest('form');
  if (!formToSubmit) {
    return;
  }

  const formData = new FormData(formToSubmit);

  formData.append('_method', 'PATCH');

  // NOTE: fix to send data to FormType when uncheck (required to include 'false' in 'false_values' option for CheckboxType)
  // @see https://symfony.com/doc/current/reference/forms/types/checkbox.html#false-values
  if ('checkbox' === element.type && !element.checked) {
    formData.append(element.name, 'false');
  }

  const actionUrl = element.closest('form')?.action;
  if (!actionUrl) {
    return;
  }

  // NOTE: use fetch (not axios) to post request as PATCH using symfony '_method' option in request (http_method_override must be set to true!)
  const response = await fetch(actionUrl, {
    credentials: 'same-origin',
    method: 'POST',
    body: formData,
  });

  const html = await response.text();
  const resultElements = document.createRange().createContextualFragment(html).querySelectorAll(`[data-field-update-target="${fieldUpdate}"]`);

  [...wrappers].forEach((wrapper, key) => {
    const wrapperReferer = wrapper.closest('[data-referer]')?.dataset?.referer;

    if (wrapperReferer) {
      [...resultElements].forEach(element => {
        const elementReferer = element.closest('[data-referer]');
        if (wrapper.id === element.id && elementReferer && wrapperReferer === elementReferer.dataset.referer) {
          wrapper.innerHTML = element.innerHTML;
          bindForm(wrapper);
        }
      });
    } else {
      const element = resultElements[key];
      if (wrapper.id === element.id) {
        wrapper.innerHTML = element.innerHTML;
        bindForm(wrapper);
      }
    }
  });
};
