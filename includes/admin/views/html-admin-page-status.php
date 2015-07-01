<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_tab = ! empty( $_REQUEST['tab'] ) ? sanitize_title( $_REQUEST['tab'] ) : 'status';
?>
<div class="wrap compi compi-status">
	<h2 class="nav-tab-wrapper">
	<?php echo Compi()->name; ?>
	<?php
		$tabs = apply_filters( 'compi_system_tools_tabs', array(
			'status' => __( 'System Status', COMPI_TEXT_DOMAIN ),
			'tools'  => __( 'Tools', COMPI_TEXT_DOMAIN ),
			//'import'  => __( 'Import', COMPI_TEXT_DOMAIN ),
			//'export'  => __( 'Export', COMPI_TEXT_DOMAIN ),
		) );
		foreach ( $tabs as $name => $label ) {
			echo '<a href="' . admin_url( 'admin.php?page=' . COMPI_PAGE . '-status&tab=' . $name ) . '" class="nav-tab ';
			if ( $current_tab == $name ) echo 'nav-tab-active';
			echo '">' . $label . '</a>';
		}
	?>
	</h2><br/>
	<?php
		switch ( $current_tab ) {
			case "import" :
				Compi_Admin_Status::status_port( 'import' );
				break;
			case "export" :
				Compi_Admin_Status::status_port( 'export' );
				break;
			case "tools" :
				Compi_Admin_Status::status_tools();
				break;
			default :
				Compi_Admin_Status::status_report();
				break;
		}
	?>
</div>
