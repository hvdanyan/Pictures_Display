function getUrlFromAddressBar() {
    var url = window.location.href;
    var pathname = window.location.pathname;
    var fileName = pathname.substring(pathname.lastIndexOf('/') + 1);
    var urlWithoutFileName = url.replace(fileName, '');
    return urlWithoutFileName;
}
function generateSalt() {
    return Math.floor(Math.random() * 900000000) + 100000000;
}
