
<!--<footer>
  <hr/>
  <a href="<?=SURL?>terms">Terms</a> <a href="<?=SURL?>privacy">Privacy</a> <a href="<?=SURL?>contact">Contact</a> <span id="year_made"> - StopListing</span> </footer>-->
<script src="js/vendor/modernizr.js"></script>
<script src="js/vendor/jquery.js"></script>
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script src="js/foundation.min.js"></script>
<script>
		$(document).ready(function() {
	$('#year_made').prepend(new Date().getFullYear());
		$( "#logo .fi-list" ).click(function() {
		$( "#sidebar" ).toggle("slide", "fast");
		$( "#main_wrapper" ).toggleClass( "expand_wrapper");
		});
		$( "#avatar_panel em" ).click(function() {
		$( "#avatar_panel span" ).slideToggle("fast");
		});
		
		$( "a.support" ).click(function() {
		$( ".purechat" ).toggle("slow");
		});
		$('.listing_entry td:first-child').click(function (event) {
		if (!$(event.target).is('input')) {
		$('input:checkbox', this).prop('checked', function (i, value) {
		return !value;
		});
		}
		});		
	});
	$(document).foundation();
	</script>
<script type='text/javascript'>(function () { var done = false; var script = document.createElement('script'); script.async = true; script.type = 'text/javascript'; script.src = 'https://widget.purechat.com/VisitorWidget/WidgetScript'; document.getElementsByTagName('HEAD').item(0).appendChild(script); script.onreadystatechange = script.onload = function (e) { if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) { var w = new PCWidget({ c: 'be007af6-c2c9-46f8-a9ba-5692c7d36ae0', f: true }); done = true; } }; })();</script>
