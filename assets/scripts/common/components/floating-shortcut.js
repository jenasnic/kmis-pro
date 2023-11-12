const floatingShortcut = document.getElementById('floating-shortcut');

const initScrollActions = () => {
    document.addEventListener('scroll', processScrollPosition);
};

const processScrollPosition = () => {
    // NOTE: check for Safari and others
    if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
        floatingShortcut.classList.add('active');
    } else {
        floatingShortcut.classList.remove('active');
    }
};

floatingShortcut && initScrollActions();
