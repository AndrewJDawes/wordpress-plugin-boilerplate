<?php
['query' => $query, 'binned_models' => $binned_models] = $args;
?>
<?php if (count($query->posts)) { ?>
    <section class="birthday">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/birthday.jpg" alt="">
        <div class="birthday__container maxwidth">
            <h3>Happy Birthday to Our Abby's Kids...</h3>
            <ul class="birthday__days birthday__days--desktop">
                <?php
                foreach ($binned_models as $datetime_string => $models) {
                    $datetime = DateTime::createFromFormat(FX_Things::DATE_FORMAT, $datetime_string);
                ?>
                    <li class='item'>
                        <h4><?php echo $datetime->format('l'); ?></h4>
                        <ul>
                            <?php
                            foreach ($models as $model) {
                            ?>
                                <li>
                                    <span><?php echo $model->get_name_first(); ?></span>
                                    <span><?php echo ($model->get_age_years() === 1) ? $model->get_age_years() . ' year old' : $model->get_age_years() . ' years old'; ?></span>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php
                }
                ?>
            </ul>
            <ul class="birthday__days birthday__days--mobile">
                <?php
                foreach ($binned_models as $datetime_string => $models) {
                    $datetime = DateTime::createFromFormat(FX_Things::DATE_FORMAT, $datetime_string);
                ?>
                    <li class='item'>
                        <h4><?php echo $datetime->format('l'); ?></h4>
                        <ul>
                            <?php
                            foreach ($models as $model) {
                            ?>
                                <li>
                                    <span><?php echo $model->get_name_first(); ?></span>
                                    <span><?php echo ($model->get_age_years() === 1) ? $model->get_age_years() . ' year old' : $model->get_age_years() . ' years old'; ?></span>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </section>
<?php } ?>