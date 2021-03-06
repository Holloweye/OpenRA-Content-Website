function post_to_url(path, params, method) {
    method = method || "post"; // Set method to post by default, if not specified.

    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key);
        hiddenField.setAttribute("value", params[key]);

        form.appendChild(hiddenField);
    }

    document.body.appendChild(form);
    form.submit();
}

function strip_tags (input, allowed) {
    allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
    return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
        return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
    });
}

function updateContent (object, content) {
    var text = document.getElementById(content).value;
	text = text.replace(/\n\r?/g, '<br />');
    document.getElementById(object).innerHTML = "<p>"+text+"</p>";
}

function updateContentWithStrip (object, content, allowed) {
	var text = strip_tags(document.getElementById(content).value, allowed);
	text = text.replace(/\n\r?/g, '<br />');
    document.getElementById(object).innerHTML = "<p>"+text+"</p>";
}

function selectAllcheckboxes(x)
{
    for(var i=0,l=x.form.length; i<l; i++)
    if(x.form[i].type == 'checkbox' && x.form[i].name != 'sAll')
    x.form[i].checked=x.form[i].checked?false:true
}

function textCounter(field,cntfield,maxlimit)
{
    if (field.value.length > maxlimit) // if too long...trim it!
    field.value = field.value.substring(0, maxlimit);
    // otherwise, update 'characters left' counter
    else
    cntfield.value = maxlimit - field.value.length;
}

function confirmDelete(desc)
{
    var agree=confirm('Are you sure you want to'+desc+'?');
    if (agree)
    return true ;
    else
    return false ;
}

function confirmReport(desc)
{
    var agree = prompt('To'+desc+', enter a reason:','');
    if (agree != null && agree != '' && agree != false)
    {
	var params = {'report_reason':agree};
	post_to_url('',params,'POST');
	return true ;
    }
    else
    return false ;
}
