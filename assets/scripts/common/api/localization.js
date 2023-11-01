import axios from 'axios';

let localizationClient = null;

export const getLocalizationClient = () => {
  if (!localizationClient) {
    const localizationBaseUrl = process.env.LOCALIZATION_BASE_URL;
    const localizationKey = process.env.LOCALIZATION_KEY;

    localizationClient = new LocalizationClient(localizationBaseUrl, localizationKey);
  }

  return localizationClient;
}

class LocalizationClient {
  httpClient = null;

  constructor(baseURL, localizationKey) {
    this.httpClient = axios.create({
      baseURL,
      timeout: 5000,
      headers: {
        'authorization': `${localizationKey}`,
      },
    });
  }

  async geocode(search) {
    const response = await this.httpClient.get(`/${search}`);

    return response.data;
  }
}
