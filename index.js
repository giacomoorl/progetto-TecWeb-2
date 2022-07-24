
window.onload = () => {
    const urlParams = new URLSearchParams(window.location.search);
    const category = urlParams.get('category') ? urlParams.get('category') : 'all';
    const link = document.getElementById(category);
    link.removeAttribute('href');
};