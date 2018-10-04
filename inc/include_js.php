<script type="text/javascript" src="<?=JS_PATH?>/jquery.min.js"></script>
<script type="text/javascript" src="<?=JS_PATH?>/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=JS_PATH?>/validator.js"></script>
<script type="text/javascript" src="<?=JS_PATH?>/jquery.validate.js"></script>
<script type="text/javascript" src="<?=JS_PATH?>/custom.min.js"></script>
<script type="text/javascript" src="<?=JS_PATH?>/tcal.js"></script>
<script type="text/javascript" src="<?=JS_PATH?>/multiple-select.js"></script>
<script type="text/javascript" src="<?=JS_PATH?>/canvasjs.min.js"></script>
<script type="text/javascript" src="<?=JS_PATH?>/script.js"></script>
<script type="text/javascript" src="<?=JS_PATH?>/excellentexport.js"></script>
<script type="text/javascript" src="<?=JS_PATH?>/jquery-ui.js"></script>
<script type="text/javascript" src="<?=JS_PATH?>/FileSaver.js"></script>
<script type="text/javascript" src="<?=JS_PATH?>/jquery.wordexport.js"></script>
<script src="<?=SOURCE_ROOT?>/common/select2/dist/js/select2.min.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $("a.word-export").click(function(event) {
            $("#printableArea").wordExport();
        });
    });
    </script>
	
	

	
	<script type='text/javascript'>
   $(function() {
    $( ".tcal_future" ).datepicker({  maxDate: new Date() });
  });

   $(document).ready(function(){
    $('.tcal_past').datepicker(
    { 
    minDate: 0,
    beforeShow: function() {
    $(this).datepicker('option', 'maxDate', $('#to').val());
    }
    });
    
    });
   
function PopupCenter(url, title, w, h) {  
    // Fixes dual-screen position                         Most browsers      Firefox  
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;  
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;  
              
    width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;  
    height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;  
              
    var left = ((width / 2) - (w / 2)) + dualScreenLeft;  
    var top = ((height / 2) - (h / 2)) + dualScreenTop;  
    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + 800 + ', height=' + 800 + ', top=' + top + ', left=' + left);  
  
    // Puts focus on the newWindow  
    if (window.focus) {  
        newWindow.focus();  
    }  
} 
</script>


<script type="text/javascript">
 
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>

<script type="text/javascript">
    function addValidator() {
        $.validator.addMethod("alpha_with_space", function (value, element) {
            return this.optional(element) || /^[a-zA-Z\s]+$/i.test(value);
        }, "Invalid Text");
        $.validator.addMethod("alpha_no_space", function (value, element) {
            return this.optional(element) || /^[a-zA-Z][a-zA-Z\\s]+$/i.test(value);
        }, "Invalid Text");
        $.validator.addMethod("alpha_num_no_space", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+$/i.test(value);
        }, "Invalid Text");
        $.validator.addMethod("user_name", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9_.]+$/i.test(value);
        }, "Invalid Text");
        $.validator.addMethod("file_name", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9_]+$/i.test(value);
        }, "Invalid Text");
        $.validator.addMethod("decimal", function (value, element) {
            return this.optional(element) || /^[0-9.]+$/i.test(value);
        }, "Invalid Text");
        $.validator.addMethod("alpha_num_with_space", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9 ]+$/i.test(value);
        }, "Invalid Text");
        $.validator.addMethod("date_slash", function (value, element) {
            return this.optional(element) || /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/i.test(value);
        }, "Invalid Date");
        $.validator.addMethod("date_hyphen", function (value, element) {
            return this.optional(element) || /^(((0[1-9]|[12]\d|3[01])\-(0[13578]|1[02])\-((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\-(0[13456789]|1[012])\-((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\-02\-((19|[2-9]\d)\d{2}))|(29\-02\-((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/i.test(value);
        }, "Invalid Date");
        $.validator.addMethod("mobile_no", function (value, element) {
            return this.optional(element) || /^[0-9]{10}$/i.test(value);
        }, "Invalid Mobile No.");
    }
</script>