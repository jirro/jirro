<?php $this->layout('Account::_layout/blank'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Please Sign In</h3>
                </div>
                <div class="panel-body">
                    <?php if (! empty($this->flashBag()->peekAll())): ?>
                    <div class="flash-messages">
                        <?php if ($this->flashBag()->has('error')): ?>
                            <?php foreach ($this->flashBag()->get('error') as $error): ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <?php print $error; ?>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ($this->flashBag()->has('success')): ?>
                            <?php foreach ($this->flashBag()->get('success') as $success): ?>
                            <div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <?php print $success ?>
                            </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <form role="form" method="post">
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="Username/E-mail" name="identity" type="text" autofocus>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Password" name="credential" type="password" value="">
                            </div>
                            <button class="btn btn-lg btn-success btn-block" type="submit">Sign in</button>
                            <a class="btn btn-lg btn-primary btn-block" href="<?php print $this->baseUrl('register'); ?>">Register</a>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
