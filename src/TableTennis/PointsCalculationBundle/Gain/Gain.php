<?php

namespace TableTennis\PointsCalculationBundle\Gain;

class Gain
{
    public static function getGain($mensual_points, $opponent_point, $scoringTable_list, $opponent_status) {
        $rangePointsAway = 0;
        $gain = 0;

        $pointsAway = $mensual_points - $opponent_point;
        $pointsAwayAbs = abs($pointsAway);

        for($j=0; $j<8; $j++){
            if($pointsAwayAbs >= $scoringTable_list[$j]->points_away && $pointsAwayAbs < $scoringTable_list[$j+1]->points_away){
                $rangePointsAway = $j;
            }
        }

        if($pointsAwayAbs >= $scoringTable_list[8]->points_away){
            $rangePointsAway = 8;
        }

        if($pointsAway < 0){ // Victoire anormale / Défaite normale
            if($opponent_status == 'v'){ // Victoire anormale
                $gain = $scoringTable_list[$rangePointsAway]->anormal_victory;
            }else{ // Défaite normale
                $gain = $scoringTable_list[$rangePointsAway]->normal_defeat;
            }
        }else{ // Victoire normale / Défaite anormale
            if($opponent_status == 'v'){ // Victoire normale
                $gain = $scoringTable_list[$rangePointsAway]->normal_victory;
            }else{ // Défaite anormale
                $gain = $scoringTable_list[$rangePointsAway]->anormal_defeat;
            }
        }
        return $gain;
    }
}