<span id="music"><audio src='./Audio/Tarot/MoldeCanticle.mp3' autoplay='autoplay' loop='loop'></audio></span>
<div id="CardsBox">
    <?php
    if($num){
        for($i=1;$i<=$num;$i++){
            echo "<div class='cards'>
<img src='Image/Tarot/TarotCards/TarotBack.jpg' class='tarot'>
<div class='showback'>
<img src='./Image/Tarot/TarotCards/showback.jpg' class='showbackcards'>
</div>
<span></span>
<p class='showname'>author:LLY</p>
</div>";
        }
    }
    ?>
</div>

