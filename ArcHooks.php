<?php

define( 'ARCCDN_SESSION_KEY', 'arccdn-rollout-roll' );

class ArcHooks {
	public static function addArcScript( OutputPage $out, $skin ) {
		global $wgArcWidgetID, $wgArcRolloutPercentage;
		if ( !$wgArcWidgetID ) return;
		if ( $wgArcRolloutPercentage == 100 ) {
			$roll = 0;
			$comment = wfMessage( 'arccdn-comment-rollout-everyone' )->escaped();
		} else {
			$session = $skin->getRequest()->getSession();
			if ( !$session->exists( ARCCDN_SESSION_KEY ) ) {
				$session->set( ARCCDN_SESSION_KEY, rand( 1, 100 ) );
			}
			$roll = $session->get( ARCCDN_SESSION_KEY );
			$comment = wfMessage(
				'arccdn-comment-rollout-percentage',
				$roll, $wgArcRolloutPercentage
			)->escaped();
		}
		$comment = wfMessage( 'arccdn-comment' )->rawParams( $comment )->escaped();
		if ( $roll <= $wgArcRolloutPercentage ) {
			$out->addHeadItem( 'arccdn-widget', <<<EOS
<!-- $comment -->
EOS );
		}
	}
}
