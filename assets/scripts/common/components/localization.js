import debounce from 'lodash/debounce';

import { getLocalizationClient } from '../api/localization';

import '../../../styles/common/_localization.scss';

export const bindAddressLocalization = (element) => {
  element.querySelectorAll('[data-localization]').forEach((localization) => {
    const searchInput = localization.querySelector('[data-localization-search]');
    const wrapper = localization.querySelector('[data-localization-wrapper]');
    const street = localization.querySelector('[data-localization-street]');
    const zipCode = localization.querySelector('[data-localization-zipcode]');
    const city = localization.querySelector('[data-localization-city]');

    if (!searchInput || !wrapper || !street || !zipCode || !city) {
      return;
    }

    localize(searchInput, wrapper, street, zipCode, city)
  });
}

const localize = (
  source,
  suggestionWrapper,
  street,
  zipCode,
  city,
  department,
  region,
) => new AddressLocalization(source, suggestionWrapper, street, zipCode, city, department, region)

class AddressLocalization {
  delay = 300;
  locked = false;
  queryCache = new Map();

  source = null;
  suggestionWrapper = null;
  street = null;
  zipCode = null;
  city = null;
  department = null;
  region = null;

  constructor(
    source,
    suggestionWrapper,
    street,
    zipCode,
    city,
    department,
    region
  ) {
    this.source = source;
    this.suggestionWrapper = suggestionWrapper;
    this.street = street;
    this.zipCode = zipCode;
    this.city = city;
    this.department = department;
    this.region = region;

    this.bind()
  }

  setDelay(delay) {
    this.delay = delay;
  }

  bind() {
    this.source.addEventListener('keydown', debounce(({ target, key }) => {
      if (['Escape', 'Tab'].includes(key)) {
        return;
      }

      this.suggest(target.value);
    }, this.delay));

    this.source.addEventListener('focus', ({ target }) => {
      this.suggest(target.value);
    });

    document.addEventListener('click', ({ target }) => {
      let targetElement = target;

      do {
          if (targetElement == this.source || targetElement == this.suggestionWrapper) {
              return;
          }
      } while (targetElement == targetElement.parentNode);

      this.hideWrapper();
    });

    document.addEventListener('keydown', ({ key }) => {
      if (['Escape', 'Tab'].includes(key)) {
        this.hideWrapper();
      }
    })
  }

  async suggest(text) {
    if (this.locked) {
      return;
    }

    if ('' === text) {
      return;
    }

    this.showWrapper();

    const response = await this.getSuggestions(text);
    if (!response) {
      return;
    }

    const markup = `
      <ul>
        ${response.map((location, key) => `
          <li data-key="${key}">${this.buildSuggestionText(location)}</li>
        `).join('')}
      </ul>
    `;

    this.suggestionWrapper.innerHTML = markup;

    this.suggestionWrapper.querySelectorAll('[data-key]').forEach((element) => {
      element.addEventListener('click', () => this.selectLocation(response[element.dataset.key || '']));
    });
  }

  async getSuggestions(text) {
    const cleanedText = text.trim();

    if (!this.queryCache.has(cleanedText)) {
      const client = getLocalizationClient();
      if (!client) {
        return;
      }

      this.queryCache.set(cleanedText, client.geocode(cleanedText, true));
    }

    return this.queryCache.get(cleanedText);
  }

  selectLocation(location) {
    this.locked = true;

    this.hideWrapper();

    this.street && (this.street.value = location.street) && this.street.focus();
    this.zipCode && (this.zipCode.value = location.zipCode) && this.zipCode.focus();
    this.city && (this.city.value = location.city) && this.city.focus();
    this.department && (this.department.value = location.department) && this.department.focus();
    this.region && (this.region.value = location.region) && this.region.focus();

    this.locked = false;
  }

  buildSuggestionText(localization) {
    return [
      localization.street || '',
      [
        localization.city || '',
        localization.zipCode ? `(${localization.zipCode})` : '',
      ].filter(n => n).join(' '),
      localization.department || '',
      localization.region || '',
    ].filter(n => n).join(', ');
  }

  showWrapper = () => this.suggestionWrapper && this.suggestionWrapper.classList.add('suggesting');
  hideWrapper = () => this.suggestionWrapper && this.suggestionWrapper.classList.remove('suggesting');
}
