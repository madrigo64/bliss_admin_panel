<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */

    // Инстанцирование объекта `Krugozor_Pagination_Helper`,
    // в него передаётся объект класса `Krugozor_Pagination_Manager` $paginationManager

    $paginationHelper = new Krugozor_Pagination_Helper($paginationManager);

    // Настройка внешнего вида пагинатора
                       // Хотим получить стандартный вид пагинации
    $paginationHelper->setPaginationType(Krugozor_Pagination_Helper::PAGINATION_NORMAL_TYPE)
                       // Устанавливаем CSS-класс каждого элемента <a> в интерфейсе пагинатора
                     ->setCssNormalLinkClass("normal_link")
                       // Устанавливаем CSS-класс элемента <span> в интерфейсе пагинатора,
                       // страница которого открыта в текущий момент.
                     ->setCssActiveLinkClass("active_tnt_link")
                       // Параметр для query string гиперссылки
                     ->setRequestUriParameter("param_1", "value_1")
                       // Параметр для query string гиперссылки
                     ->setRequestUriParameter("param_2", "value_2")
                       // Устанавливаем идентификатор фрагмента гиперссылок пагинатора
                     ->setFragmentIdentifier("result1");

?>


    <div id="tnt_pagination">
         All records: <strong><?php echo  $paginationHelper->getPagination()->getCount()?></strong>
        <?php if ($paginationHelper->getPagination()->getCount()): ?>
            <br /><br /><span>Pages:</span>
            <?php echo $paginationHelper->getHtml()?>
        <?php endif; ?>
    </div>
<br>
<br>
<br>

<div class="clean-red">
    <?php
    if(isset($_SESSION['msg_red']) && $_SESSION['msg_red']!='') {
        
        echo $_SESSION['msg_red'];
        unset($_SESSION['msg_red']);
    }
   
     
    ?>
</div>
<div class="clean-green"><?php 

    if(isset($_SESSION['msg_green']) && $_SESSION['msg_green']!='') {
        
        echo $_SESSION['msg_green'];
        unset($_SESSION['msg_green']);
    }

?></div>
<br>
<?php

        if(isset($count_players) && $count_players >0):  ?>
            <table border="0" width="100%" cellpadding="0" cellspacing="0" class="players-table">
                    <tr>
                        <th  width="5%">Status</th>
                        <th  width="5%">Unique id</th>
                        <th  width="5%">Position</th>
                        <th  width="10%">Survival time/created</th>
                        <th  width="5%">Last Updated</th>
                        <th  width="5%">Player Name</th>
                        <th class="player_inventory" width="30%">Inventory preview</th>
                        <th  width="30%">Backpack preview</th>
                    </tr>
                    <?php echo $palyers_table_rows; ?>				
            </table>


   <?php endif;?> 

<br>
<br>


    <div id="tnt_pagination">
        All records: <strong><?php echo  $paginationHelper->getPagination()->getCount()?></strong>
        <?php if ($paginationHelper->getPagination()->getCount()): ?>
            <br /><br /><span>Pages:</span>
            <?php echo $paginationHelper->getHtml()?>
        <?php endif; ?>
    </div>


