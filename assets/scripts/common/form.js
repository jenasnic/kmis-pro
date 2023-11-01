import { bindInputMask, bindNumberInput } from './components/input-mask';
import { bindConfirmForm } from './components/confirm';
import { bindBulmaFile } from './components/bulma-file';
import { bindFormFieldUpdate } from './components/field-update';
import { bindCollectionType } from './components/collection-type';
import { bindSortable } from './components/sortable';
import { bindAddressLocalization } from './components/localization';

const mapping = {
  '[data-mask-input]': bindInputMask,
  '[data-number-input]': bindNumberInput,
  '[data-input-file]': bindBulmaFile,
  '[data-field-update]': bindFormFieldUpdate,
  '[data-collection-type]': bindCollectionType,
  '[data-sortable]': bindSortable,
  '[data-localization]': bindAddressLocalization,
  'form[data-confirm]': bindConfirmForm,
};

export const bindForm = (element) => {
  Object.entries(mapping).forEach(([selector, binder]) => {
    [...(element || document).querySelectorAll(selector)].forEach((element) => {
        if ('[data-localization]' === selector) {
          binder(element.parentNode)
        } else {
          binder(element)
        }
      }
    )});
};

bindForm();

bindAddressLocalization(document);
