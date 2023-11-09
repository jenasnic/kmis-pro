import TurningItem from '../common/components/turning-item';

const bindServiceTarget = (wrapper) => {
  new TurningItem(wrapper.querySelector('.target-list'), {
    speed: 5000,
    callbackSelection: (item) => toggleDetail(wrapper, item.dataset.targetItem),
  });
};

const toggleDetail = (wrapper, index) => {
  [...wrapper.querySelectorAll('.target-detail')].forEach(detail => detail.classList.remove('active'));
  const targetDetail = document.querySelector(`#service-target .target-detail[data-target-detail="${index}"]`);
  targetDetail.classList.add('active');
};

document.getElementById('service-target') && bindServiceTarget(document.getElementById('service-target'));
