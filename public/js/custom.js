// config Autonumeric format
let configAutoNumeric = {
    decimalPlaces: '2',
    decimalCharacter: ',',
    digitGroupSeparator: '.',
}

// create autonumeric
function inputRibuan(input) {
    return new AutoNumeric.multiple(input, configAutoNumeric);
}

//
function convertDouble(input) {
    return parseFloat(input.replace(/\./g, '').replace(',', '.'));
}

//
function formatRibuan(nilai) {
    return nilai.toLocaleString('id-ID');
}