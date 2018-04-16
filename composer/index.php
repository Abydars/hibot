<?php
require_once __DIR__ . '/vendor/autoload.php';
use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;

//Direct Probablity

 $chest_pain = 1;
 $dizziness = 2;
 $sweating = 3;
 $breathing_difficulty = 4;
 $eyediscomfort = 5;
 $haedache = 6;
 $sickstomach= 7;
 $bodypain = 8;
 $energyloss=9;
 $lowbp = 10;
 $highbp = 11;
 $fever=12;

//
// $samples = [[1, 6], [1, 5], [1, 4],[6,4],[1,5],[1,4],[3,2]];
// $labels = ['Migraine', 'sugar', 'cervical','sugar','cervical','sugar','phatri'];
//
// $classifier = new SVC(Kernel::LINEAR, $cost = 1000);
// $classifier->train($samples, $labels);


//echo $classifier->predict([3,6]);


//$classifier->predict([[3, 2], [1, 5]]);
 //return ['b', 'a']



 $samples = [[1,3], [2,3], [1, 2],[2,3],[1,3],[1,2],[2,3],[2,3],[2,3],[2,3],[3,4],[3,4],[3,4],[3,4],[4,5]
 ,[4,5],[4,5],[5,6],[5,6],[5,6],[6,7],[6,7],[6,7],[8,9],[8,9],[8,9],[8,9],[10,12],[10,12],[10,12],[11,12],
 [11,12],[11,12]  ]  ;
 $labels = ['anxiety disorder', 'Faintness', 'Flu','Poisioning', 'shallow breathing' , 'Muscle Cramps', 'Viral Infection',
               'Heart Burn' , 'HyperVentilation', 'bacterial infection', 'Collapsed Lungs', 'Asthma', 'Migraine','motion sickness', 'influenza', 'Faintness',
             'Flu' , 'EyeInfection', 'Migraine','soasonaldepression', 'periods','biopolar disorder', 'Muscle Cramps', 'motion', 'fever'
            ,'Viral pneumonia', 'Common Cold', 'Strep Thorat' , 'Bacterial Pneumonia','hypertension', 'kidney Infection'];

$classifier = new SVC(
    Kernel::LINEAR, // $kernel
    2.0,            // $costx
    3,              // $degree
    null,           // $gamma
    0.0,            // $coef0
    0.001,          // $tolerance
    100,            // $cacheSize
    true,           // $shrinking
    true            // $probabilityEstimates, set to true
);

$classifier->train($samples, $labels);
$a= $classifier->predictProbability([8,9],[1,2]);
$a1=1;
$a2= 2;

$b= $classifier->predictProbability([$a1,$a2]);
// echo implode(',' , $labels);
// echo " <br> ";
// echo implode(',',$a);
// echo " <br> ";
//echo implode(',',$b);
$c= $classifier->predictProbability([1,5]);
echo '<pre>'; var_dump($c);


// return ['a' => 0.349833, 'b' => 0.650167]


// return ['a' => 0.349833, 'b' => 0.650167]

//$classifier->predictProbability([[3, 2], [1, 5]]);
// return [
//   ['a' => 0.349833, 'b' => 0.650167],
//   ['a' => 0.922664, 'b' => 0.0773364],
// ]

?>
