<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>
<script type="text/javascript" src="../js/jquery.validate.js"></script>
<script type="text/javascript" src="../js/custom.min.js"></script>
<script type="text/javascript" src="../js/tcal.js"></script>
<script type="text/javascript" src="../js/multiple-select.js"></script>
<script type="text/javascript" src="../js/canvasjs.min.js"></script>
<script type="text/javascript" src="../js/script.js"></script>
<script type="text/javascript" src="../js/excellentexport.js"></script>
<script type="text/javascript" src="../js/jquery-ui.js"></script>
<script type="text/javascript" src="../js/FileSaver.js"></script>
<script type="text/javascript" src="../js/jquery.wordexport.js"></script>
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
   
    </script>