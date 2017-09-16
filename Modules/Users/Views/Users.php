<?php
    use Monstercms\Core\Lang;
?>


<div class="btn-group">
    <a href="/Users/Add" class="btn btn-default">
        <i class="fa fa-plus-square" aria-hidden="true"></i>
        <?=Lang::get('Users.add')?>
    </a>
</div>
<table class="user">
    <thead>
        <tr >
            <th width="10%">ID</th>
            <th width="40%"><?=Lang::get('Users.login')?></th>
            <th width="40%"><?=Lang::get('Users.role')?></th>
            <th width="10%"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $user):?>

            <tr>
                <td class="id"><?=$user['id']?></td>
                <td><?=$user['login']?></td>
                <td>
                    <?php if (isset($roles[$user['role']])):?>
                        <?=$roles[$user['role']]?>
                    <?php endif?>
                </td>
                <td>

                   <a href="/Users/Edit/Id/<?=$user['id']?>" class="fa fa-pencil btn btn-default min" title="<?=Lang::get('Users.edit');?>"></a>
                    <?php if((int) $thisUserId !== (int) $user['id']):?>
                        <a  href="/Users/Delete/Id/<?=$user['id']?>" class="fa fa-times btn btn-default min " title="<?=Lang::get('Users.delete');?>"></a>
                    <?php endif?>
                </td>
            </tr>
        <?php endforeach?>

    </tbody>
</table>
<script>
    $(function(){

        $('.user a.fa-times').click(function(e){
            if(!confirm('<?=Lang::get('Users.deleteUser')?>')) {
                e.preventDefault();
            }
        });
    });
</script>