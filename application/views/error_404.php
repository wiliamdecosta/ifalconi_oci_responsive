<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->

		<!-- #section:pages/error -->
		<div class="error-container">
			<div class="well">
				<h1 class="grey lighter smaller">
					<span class="blue bigger-125">
						<i class="ace-icon fa fa-sitemap"></i>
						404
					</span>
					Page Not Found
				</h1>

				<hr />
				<h4 class="lighter smaller">We looked everywhere but we couldn't find it!. The link on the referring page seems to be wrong or outdated. Please inform the author of that page about the error. <br/> </br> If you think this is a server error, please contact the webmaster.</h4>

				<hr />
				<div class="space"></div>

				<div class="center">
					<a href="#" class="btn btn-primary" id="btn-dashboard">
						<i class="ace-icon fa fa-tachometer"></i>
						Dashboard
					</a>
				</div>
			</div>
		</div>

		<!-- /section:pages/error -->

		<!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->

<script>
jQuery(function($) {
    $("#btn-dashboard").on(ace.click_event, function() {
		 $(".nav-menu-content").removeClass("active");
		 $('.nav-menu-content[data-source="dashboard.php"]').addClass("active");
		 loadContent("dashboard.php");
	});
});
</script>