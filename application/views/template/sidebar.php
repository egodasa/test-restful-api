<!-- Left side column. contains the logo and sidebar -->
		<aside class="main-sidebar">
			<!-- sidebar: style can be found in sidebar.less -->
			<section class="sidebar">
				<!-- sidebar menu: : style can be found in sidebar.less -->
				<ul class="sidebar-menu" data-widget="tree">
					<li class="header">MAIN NAVIGATION</li>
					<?php
			foreach($menu as $m){
				if(!isset($m['child'])){
					echo '<li><a href="'.$m['url'].'"><i class="'.$m['icon'].'"></i> <span>'.$m['text'].'</span></a></li>';
				}else{
					echo '
						<li class="treeview">
							<a href="#">
							<i class="'.$m['icon'].'"></i> <span>'.$m['text'].'</span>
							<span class="pull-right-container">
							  <i class="fa fa-angle-left pull-right"></i>
							</span>
							</a>
							<ul class="treeview-menu">';
							foreach($m['child'] as $c){
								echo '<li><a href="'.$c['url'].'"><i class="'.$c['icon'].'"></i> '.$c['text'].'</a></li>';
							}
							echo '</ul></li>';
				}
			}
        ?>
				</ul>
			</section>
			<!-- /.sidebar -->
		</aside>
