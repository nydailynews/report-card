// aaaaah
function update_form(el)
{
    // Convert letter grade to numeric value
    var lookup = {
        a: 4,
        b: 3,
        c: 2,
        d: 1,
        f: 0
    };
    var letters = ['a', 'b', 'c', 'd', 'f'];
    var letter_count = 5;
    for ( i = 0; i < letter_count; i++ )
    {
        jQuery("#slug .letter_grades #" + letters[i]).removeClass('letter_highlight');
    }

    jQuery(el).addClass('letter_highlight');

	el.form.preventDefault();
	// We know the grade_input hidden form field is the fifth element in the form
	// * THIS IS BRITTLE AND SHOULD BE ADDRESSED *
	var input = el.form.elements[5].setAttribute('value', lookup[el.name]);
    //jQuery('#slug #grade_input').val(lookup[el.id]);
	console.log($(el.form).serializeArray());
	console.log($(el.form).attr('action'));
	console.log(el.name);
	return false;
	window.f = el.form;
}

function lookup_letter_grade(avg)
{
    // Takes a float and returns a string letter-grade representation of that value.
    if ( avg > 3.8 ) return 'A';
    if ( avg > 3.5 ) return 'A-';
    if ( avg > 3.2 ) return 'B+';
    if ( avg > 2.8 ) return 'B';
    if ( avg > 2.5 ) return 'B-';
    if ( avg > 2.2 ) return 'C+';
    if ( avg > 1.8 ) return 'C';
    if ( avg > 1.5 ) return 'C-';
    if ( avg > .5 ) return 'D';
    return 'F';
}

var on_submit = function(e)
{
    var post_data = $(this).serializeArray();
    //var formURL = $(this).attr('action');
	console.log(e);
	window.e = e;
	// Remove the onclick handlers from the buttons.
	// This slice.call part turns the nodelist into an array which we then use map() to axe the onclick.
	[].slice.call(document.querySelectorAll('#' + e.id + ' button')).map(function(x) { x.setAttribute('onclick', '') });
    $.ajax(
    {
        url: '../handler/',
        type: 'POST',
        data: post_data,
        success:function(data, text_status, jqXHR) 
        {
            // The data returned from the server will be two values, separated
            // by a comma: the grade average, and the number of votes. 
            var values = data.split(',');
            grade_average = values[0];
            voters = values[1];
            var letter_grade = lookup_letter_grade(grade_average);
            $('#" . $slug . " #result a').text(letter_grade);
            $('#" . $slug . " #result p').text('With ' + voters + ' votes.');
            //console.log(data, text_status, jqXHR);
        },
        error: function(jqXHR, text_status, error_thrown) 
        {
            console.log(data, text_status, error_thrown);
        }
    });
    //e.preventDefault(); // STOP default action
    e.unbind(); // unbind. to stop multiple form submit.
};
 
//$('#slug').submit();
