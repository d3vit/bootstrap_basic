
    <div id="footer">
        <div class="container">
            <div class="row divide">
                <div class="col-md-4">
                    <?php dynamic_sidebar('Footer Left');?>
                </div>
                <div class="col-md-4">
                    <?php dynamic_sidebar('Footer Middle');?>
                </div>
                <div class="col-md-4">
                    <?php dynamic_sidebar('Footer Right');?>
                </div>
            </div>    
        </div>
    </div>

    <?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
	?>

</body>
</html>