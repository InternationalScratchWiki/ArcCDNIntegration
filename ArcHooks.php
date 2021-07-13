<?php

define( 'ARCCDN_SESSION_KEY', 'arccdn-rollout-roll' );

class ArcHooks {
	public static function addArcScript( OutputPage $out, $skin ) {
		global $wgArcWidgetID, $wgArcRolloutPercentage, $wgRequest;
		$session = $wgRequest->getSession();
		if ( !$session->exists( ARCCDN_SESSION_KEY ) ) {
			$session->set( ARCCDN_SESSION_KEY, rand( 1, 100 ) );
		}
		$roll = $session->get( ARCCDN_SESSION_KEY );
		if ( $roll <= $wgArcRolloutPercentage ) {
			$out->addHeadItem( 'arccdn-widget', <<<EOS
<script async src="https://arc.io/widget.min.js#$wgArcWidgetID"></script>
EOS );
		}
	}
}
