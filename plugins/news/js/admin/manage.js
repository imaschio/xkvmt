$(function()
{
    if(Ext.get('date'))
    {
        new Ext.form.DateField(
        {
            allowBlank: false,
            format: 'Y-m-d',
            applyTo: 'date'
        });
    }

    $("textarea.ckeditor_textarea").each(function()
    {
        if(!CKEDITOR.instances[$(this).attr("id")])
        {
            intelli.ckeditor($(this).attr("id"), {toolbar: 'User'});
        }
    });
});