// function to convert large numbers in human readable numbers
// passing a negative number for decimals returns the unmodified number
function getHumanReadData(number, decimals, trimZeros) {
  if (decimals === undefined) {
    decimals = 0;
  }

  if (decimals < 0) {
    return number;
  }

  number = parseFloat(number);
  var abs = Math.abs(number);

  /* code from MDN (public domain)
   * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Math/round$revision/981487#Decimal_rounding
  */
  /**
   * Decimal adjustment of a number.
   *
   * @param {String}  type  The type of adjustment.
   * @param {Number}  value The number.
   * @param {Integer} exp   The exponent (the 10 logarithm of the adjustment base).
   * @returns {Number} The adjusted value.
   */
  function decimalAdjust(type, value, exp) {
    // If the exp is undefined or zero...
    if (typeof exp === 'undefined' || +exp === 0) {
      return Math[type](value);
    }
    value = +value;
    exp = +exp;
    // If the value is not a number or the exp is not an integer...
    if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
      return NaN;
    }
    // Shift
    value = value.toString().split('e');
    value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
    // Shift back
    value = value.toString().split('e');
    return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
  }

  function toFixed(number, decimals) {
    var s = decimalAdjust('round', number, -decimals).toFixed(decimals);
    return trimZeros ? s.replace(/\.0+$|(\..*?)0+$/,'$1') : s;
  }

  // this function prints larger number in OECD format 10 000.23 usw.
  function spaceSeparateNumber(val){
    while (/(\d+)(\d{3})/.test(val.toString())){
      val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ' ' + '$2');
    }
    return val;
  }

  // this function prints larger number in US format 10,000.23 usw.
  function commaSeparateNumber(val){
    while (/(\d+)(\d{3})/.test(val.toString())){
      val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
    }
    return val;
  }

  if(abs > 1e12) {
    return toFixed(number / 1e12, decimals) + '&nbsp;' + window.lang_labels['trillion'];
  } else if(abs > 1e9) {
    return toFixed(number / 1e9, decimals) + '&nbsp;' + window.lang_labels['billion'];
  } else if(abs > 1e6) {
    return toFixed(number / 1e6, decimals) + '&nbsp;' + window.lang_labels['million'];
  } else if(abs > 1e5) {
    return toFixed(number / 1e3, decimals) + '&nbsp;' + window.lang_labels['thousand'];
  } else {
    return spaceSeparateNumber(toFixed(number, decimals));
  }
}
