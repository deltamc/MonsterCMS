<?
$form_items = array
(
    array
    (
            'name' => "date",
            'type' => 'date',
            'label' => 'Дата:',
        'valid' => array
        (
            'required'
        ),
    ),
    array
    (
        'type'=>'html',
        'html'=>'<style>
.picker__holder
{
    -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=1)" !important;
    filter:alpha(opacity=1) !important;
    -moz-opacity:1 !important;
    opacity:1 !important;
    box-shadow:none !important;
    margin-top:20px !important;
    width:80% !important;
}
.picker__button--close, .picker__button--today, .picker__button--clear
{
    display:none !important;
}</style>'
    )

)
?>