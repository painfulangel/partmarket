<?php
/* @var $this Controller */
switch ($mode) {
    case LUser::ACTIVE_STATE:
        $this->pageTitle = Yii::t('lily', '{appName} - Activate user', array('{appName}' => Yii::app()->config->get('Site.SiteName')));
        $this->breadcrumbs = array(
            Yii::t('lily', 'User') => $this->createUrl('user/view', array('uid' => $user->uid)),
            Yii::t('lily', 'Activate')
        );
        ?><h1><?php echo Yii::t('lily', 'Activate user'); ?></h1><?php
        break;
    case LUser::DELETED_STATE:
        $this->pageTitle = Yii::t('lily', '{appName} - Delete user', array('{appName}' => Yii::app()->config->get('Site.SiteName')));
        $this->breadcrumbs = array(
            Yii::t('lily', 'User') => $this->createUrl('user/view', array('uid' => $user->uid)),
            Yii::t('lily', 'Delete')
        );
        ?><h1><?php echo Yii::t('lily', 'Delete user'); ?></h1><?php
        break;
    case LUser::BANNED_STATE:
        $this->pageTitle = Yii::t('lily', '{appName} - Ban user', array('{appName}' => Yii::app()->config->get('Site.SiteName')));
        $this->breadcrumbs = array(
            Yii::t('lily', 'User') => $this->createUrl('user/view', array('uid' => $user->uid)),
            Yii::t('lily', 'Ban')
        );
        ?><h1><?php echo Yii::t('lily', 'Ban user'); ?></h1><?php
        break;
}
?>
<form action="" method="POST">
    <p>
        <?php
        /* @var $this UserController */
        switch ($mode) {
            case LUser::ACTIVE_STATE: $txt = Yii::t('lily', !$self ? "Do you really want to activate user {user} account?" : "Do you really want to activate your account?", array('{user}' => $user->name));
                break;
            case LUser::DELETED_STATE: $txt = Yii::t('lily', !$self ? "Do you really want to delete user {user} account?" : "Do you really want to delete your account?", array('{user}' => $user->name));
                break;
            case LUser::BANNED_STATE: $txt = Yii::t('lily', "Do you really want to ban user {user} account?", array('{user}' => $user->name));
                break;
        }
        echo CHtml::encode($txt);
        ?>
    </p>
    <input type="hidden" name="approved" value="1" />
    <div>
        <a href="<?php
        echo $this->createUrl(($user->id == Yii::app()->user->id && $user->state == LUser::DELETED_STATE) ? 'logout' : 'view', array('uid' => $user->uid));
        ?>"><?php echo CHtml::encode(Yii::t('lily', "Cancel")); ?></a>
        <input type="submit" value=" <?php
        switch ($mode) {
            case LUser::ACTIVE_STATE: $txt = Yii::t('lily', !$self ? "Activate user" : "Activate my user account");
                break;
            case LUser::DELETED_STATE: $txt = Yii::t('lily', !$self ? "Delete user" : "Delete my user account");
                break;
            case LUser::BANNED_STATE: $txt = Yii::t('lily', "Ban user");
                break;
        }
        echo CHtml::encode($txt);
        ?> " />
    </div>
</form>