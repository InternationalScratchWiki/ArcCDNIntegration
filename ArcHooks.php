<?php

define( 'ARCCDN_SESSION_KEY', 'arccdn-rollout-roll' );
define( 'ARCCDN_PREFERENCE', 'arccdn-opt-in' );

class ArcHooks {
	public static function addArcScript( OutputPage $out, $skin ) {
		global $wgArcWidgetID, $wgArcRolloutPercentage;
		if ( !$wgArcWidgetID ) return;
		if ( $wgArcRolloutPercentage == 100 ) {
			$roll = 0;
			$comment = wfMessage( 'arccdn-comment-rollout-everyone' )->escaped();
		} else {
			$lookup = MediaWiki\MediaWikiServices::getInstance()->getUserOptionsLookup();
			$pref = $lookup->getBoolOption( $skin->getUser(), ARCCDN_PREFERENCE );
			if ( $pref ) {
				$roll = 0;
				$comment = wfMessage( 'arccdn-comment-opted-in' )->escaped();
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
		}
		$comment = wfMessage( 'arccdn-comment' )->rawParams( $comment )->escaped();
		if ( $roll <= $wgArcRolloutPercentage ) {
			$out->addHeadItem( 'arccdn-widget', <<<EOS
<!-- $comment -->
<script async src="https://arc.io/widget.min.js#$wgArcWidgetID"></script>
EOS );
		}
	}

	public static function onGetPreferences( $user, &$preferences ) {
		global $wgArcWidgetID, $wgArcRolloutPercentage;
		if ( !$wgArcWidgetID || $wgArcRolloutPercentage == 100 ) return;
		$preferences[ARCCDN_PREFERENCE] = [
			'type' => 'toggle',
			'section' => 'rendering',
			'label-message' => 'arccdn-opt-in',
			'help-message' => 'arccdn-opt-in-help'
		];
	}
}
