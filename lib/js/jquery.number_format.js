/**
 * jQuery plugin to format numbers
 *
 * Author: Hugo Hideki Yamashita <hugo.hideki@gmail.com>
 * Version: 2.0
 * Release date: January 01, 2009
 */

/* Examples

  alert($.number_format(132, {precision: 2}));
  // Result: 1.32

  alert($.number_format('123,45', {precision: 3, decimal: '.'}));
  // Result: 12.345
*/
(function($) {
  $.number_format = function(value, opts) {
    // Merges default options with parameter opts
    var options = $.extend({precision:     2,
                            decimal:       '.',
                            thousands:     ',',
                            default:       '0,00',
                            allow_negative: true}, opts);

    // Validates negative signal
    var signal = (value.toString().indexOf('-') == 0 && options.allow_negative) ? '-' : '';

    // Set the default value unless the input has any value
    if(get_numeric(value) == 0 || get_numeric(value) == '')
      return signal + options.default;

    // Formats the number
    var val = get_numeric(value);
        val = signal + format_value(val);

    // Return formatted number
    return val;



    // Returns the numeric part of "val", preserving the signal
    function get_numeric(val) {
      var value = val.toString().replace(/[^\d]*/g, '');
      var mult = val.toString().indexOf('-') == 0 ? -1 : 1;
      return parseInt(value * mult);
    }



    // Formats "val"
    function format_value(val) {
      var value = val.toString();
      var offset = value.charAt(0) == '-' ? 1 : 0;
      var integer_part = '';
      var decimal_part = '';
      var formatted_number = '';
      var aux = 0;

      // If there is a precision, formats the number
      if(options.precision > 0) {
        aux = offset ? (options.precision + 1) : options.precision;

        // If the number of digits is lesser than the precision, no need to format
        if(value.length <= aux) {
          integer_part = '0';

          // Adds the correct number of digits in the decimal part
          if(value.length < aux)
            for(var i = 0; i < options.precision - value.length; i++)
              decimal_part += '0';

          decimal_part += offset ? value.slice(1) : value;
          formatted_number += integer_part + options.decimal + decimal_part;

        // If the number of digits is greater than the precision, formats the number
        } else {
          integer_part = value.slice(0, value.length - options.precision);
          decimal_part = value.slice(value.length - options.precision);
          formatted_number = format_integer_part(integer_part) + options.decimal + decimal_part;
        }

      // If no precision is given, the number is integer only
      } else {
        formatted_number = format_integer_part(value);
      }

      return formatted_number;
    }



    // Formats the integer part of the number
    function format_integer_part(val) {
      var counter = 0;
      var formatted = '';
      var separator = '';
      var offset = val.charAt(0) == '-' ? 1 : 0;

      // Adds the thousands separator in every 3 digits
      for(var i = val.length - 1; i >= offset; i--) {
        separator = (counter > 0 && counter % 3 == 0) ? options.thousands : '';
        formatted = val[i] + separator + formatted;
        counter++;
      }

      return formatted;
    }
  };
})(jQuery);





/* Examples

  // Input
  <input type="text" class="numeric" value="" />

  // Brazilian currency format
  $(".numeric").number_format({precision: 2,
                               decimal: ',',
                               thousands: '.'});

  // Changing precisio to 5 decimal digits
  $(".numeric").number_format({precision: 5});
*/
(function ($) {
  $.fn.number_format = function (opts) {
    return $(this).each(function () {
      $(this).val($.number_format($(this).val(), opts));

      // Set event handlers
      $(this)

      // Formats the value
      .keyup(function() {
        $(this).val($.number_format($(this).val(), opts));
      })

      // Trigger keyup event to format the value again, if necessary
      .blur(function() {
        $(this).trigger('keyup');
      })

      // Change focus behaviour to select the value
      .focus(function() {
        $(this).select();
      });
    });
  };
})(jQuery);