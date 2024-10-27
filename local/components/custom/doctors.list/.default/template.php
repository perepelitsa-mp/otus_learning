<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

<div class="doctors-list">
    <?php foreach($arResult as $doctor): ?>
        <div class="doctor">
            <h3><?= htmlspecialchars($doctor["NAME"]) ?></h3>
            <?php if(!empty($doctor["PROTSEDURY"])): ?>
                <ul>
                    <?php foreach($doctor["PROTSEDURY"] as $procedure): ?>
                        <li>
                            <a href="#" class="procedure-link" data-procedure-id="<?= $procedure["ID"] ?>">
                                <?= htmlspecialchars($procedure["NAME"]) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>У этого врача нет процедур.</p>
            <?php endif; ?>

            <!-- Выводим наше пользовательское свойство -->
            <?php if(!empty($doctor["BOOKING_PROCEDURES_HTML"])): ?>
                <div class="booking-procedures">
                    <?= $doctor["BOOKING_PROCEDURES_HTML"] ?>
                </div>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>
</div>
