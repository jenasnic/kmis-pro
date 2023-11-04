import Picker from 'vanilla-picker';

import '../styles/_help-tool.scss';

const defaultFont = "'Teko', serif";

const initHelpTool = (helpTool) => {
    helpTool.querySelectorAll('[data-color-picker]').forEach(initColorPicker);
    initFontSelector(helpTool.querySelector('[data-font-selector]'));
};

const initColorPicker = (colorPicker) => {
    const colorType = colorPicker.dataset.colorPicker;

    const options = {
        parent: colorPicker.querySelector('.color-picker__selector'),
        popup: 'top',
        onChange: (color) => changeColor(color, colorType),
        onDone:  (color) => changeColor(color, colorType),
    };

    const picker = new Picker(options);
    picker.setColor(getColor(colorType), false);

    colorPicker.querySelector('.color-picker__revert').addEventListener('click', () => {
        const defaultColor = colorPicker.dataset.colorDefault;
        saveColor(defaultColor, colorType);
        picker.setColor(defaultColor, false);
    });
};

const initFontSelector = (fontSelector) => {
    fontSelector.addEventListener('change', (event) => {
        const font = event.target.value;
        saveFont(font);
        document.documentElement.style.setProperty('--font-title', font);
    });

    fontSelector.value = getFont();
    document.documentElement.style.setProperty('--font-title', getFont());
};

const changeColor = (color, colorType, displayColorElement) => {
    saveColor(color.hex, colorType);

    const hexaColor = color.hex.substring(0, 7);

    let root = document.documentElement;
    root.style.setProperty(`--${colorType}-color`, hexaColor);
    document.querySelector(`#help-tool [data-color-picker="${colorType}"] .color-picker__text`).innerHTML = hexaColor;
};

const saveColor = (color, colorType) => {
    localStorage.setItem(`color-${colorType}`, color);
};

const getColor = (colorType) => {
    const color = localStorage.getItem(`color-${colorType}`);

    if (null !== color) {
        return color;
    }

    return document.querySelector(`#help-tool [data-color-picker="${colorType}"]`).dataset.colorDefault;
};

const saveFont = (font) => {
    localStorage.setItem('font', font);
};

const getFont = () => {
    const font = localStorage.getItem('font');

    if (null !== font) {
        return font;
    }

    return defaultFont;
};

document.getElementById('help-tool') && initHelpTool(document.getElementById('help-tool'));
