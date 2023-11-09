/**
 * Class to create turning item (rotate 'active' class for each child of specified wrapper).
 * Available options for turning items are :
 *   - speed : turning rotation speed in ms (default to 2000)
 *   - callbackSelection (optional) : callback function to call after item rotation
 *   - startFunction (optional) : function used to start rotation
 *   - startIndex (optional) : first item index to show (default to 0)
 */
export default class TurningItem {
  wrapper;
  turning;
  currentItem;
  itemCount;
  options;

  /**
   * @param wrapper element that contains items to rotate
   * @param options rotation options
   */
  constructor(wrapper, options = {}) {
    this.wrapper = wrapper;

    const defaultOptions = {
      speed: 2000,
      callbackSelection: () => {},
      startFunction: f => f && f(),
      startIndex: 0,
    };

    this.options = { ...defaultOptions, ...options };

    this.turning = false;
    this.currentItem = this.options.startIndex;
    this.itemCount = this.wrapper.childElementCount;

    this.initActions();
    this.selectItem(this.wrapper.children[this.currentItem]);

    this.options.startFunction(this.initTurning);
  };

  /**
   * Initialize actions for wrapper.
   */
  initActions = () => {
    this.wrapper.childNodes.forEach(this.initializeItem);
  };

  /**
   * Initialize actions on an item.
   *
   * @param item
   */
  initializeItem = (item) => {
    item.addEventListener('mouseover', () => {
      this.selectItem(item);
      this.disableTurning();
    });
    item.addEventListener('mouseleave', this.enableTurning);
  };

  /**
   * Select specified item.
   *
   * @param item
   */
  selectItem = (item) => {
    this.currentItem = Array.prototype.indexOf.call(this.wrapper.children, item);
    [...this.wrapper.children].forEach(item => item.classList.remove('active'));
    item.classList.add('active');
    this.options.callbackSelection(item);
  };

  /**
   * Select current item.
   */
  selectCurrentItem = () => {
    const item = this.wrapper.children[this.currentItem];
    this.selectItem(item);
  };

  /**
   * Initialize slider for items.
   */
  initTurning = () => {
    if (this.turning) {
      return;
    }

    this.turning = true;
    setTimeout(() => { this.turnItem(); }, this.options.speed);
  };

  /**
   * Select next item of dictionary.
   */
  turnItem = () => {
    if (!this.turning) {
      return;
    }

    this.currentItem = (this.currentItem + 1) % this.itemCount;
    this.selectCurrentItem();
    setTimeout(() => { this.turnItem(); }, this.options.speed);
  };

  /**
   * Disallow slider on items.
   */
  disableTurning = () => {
    this.turning = false;
  };

  /**
   * Enable slider on items.
   */
  enableTurning = () => {
    this.turning = true;
  };
}
