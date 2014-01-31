{if WBB_THREADSMALLBUTTON_BRUNZENBAER && $__wcf->user->userID}
<script data-relocate="true">
	//<![CDATA[
		$(function() {
			$(".brunzenbaerLink a").on("click", function(e) {
				e.preventDefault();
				
				var _that = this;
				var os_threadid = $(this).data("thread-id");
				var os_postid = $(this).data("post-id");
				var os_userid = $(this).data("user-id");
				
				WCF.System.Confirmation.show('Willst Du diesen Beitrag wirklich zur "Brunzenbär des Monats"-Wahl für den Monat {@TIME_NOW|date:"F"} nominieren? Du kannst die Nominierung danach nicht mehr zurückziehen.',
					$.proxy(function (action) {
						if (action == 'confirm') {
							var brunzenbaerAPI = "http://api.schloebe.de/brunzenbaer/api.php";
							
							$.ajax({
								url:		brunzenbaerAPI,
								dataType:	"jsonp",
								data:		{ action: "add", threadid: os_threadid, postid: os_postid, userid: os_userid }
							})
							.done(function( data ) {
								if(data.success == 1) {
									jQuery(_that).addClass('active');
								}
								
								if(data.message != '') {
									var $notification = new WCF.System.Notification(data.message, data.type);
									$notification.show(null, 6000);
								}
							});
						}
					}, this));
				return false;
			});
		});
	//]]>
</script>
{/if}