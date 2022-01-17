<?php $valid = (sessionData('_previous') == site_url('test')) ? 'is-valid' : ''; ?>
<div class="container">
    <h3 class="text-center">Login form</h3>
    <div class="row justify-content-md-center">
        <div class="col-md-4">
            <form method="post" action="<?php echo site_url('test-validate'); ?>">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="text" name="email"
                           class="form-control"
                           id="email" placeholder="Enter email" value="<?php echo oldValue('email'); ?>">
                    <small id="emailHelp" class="invalid-feedback">
<!--                        --><?php //echo($errors->first('email')); ?>
                    </small>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input name="password" type="password"
                           class="form-control"
                           id="password" placeholder="Password">
                    <small id="emailHelp" class="invalid-feedback">
<!--                        --><?php //echo($errors->first('password')); ?>
                    </small>
                </div>
                <div class="form-group">
                    <label for="password2">Password</label>
                    <input name="passwordconfirm" type="password"
                           class="form-control"
                           id="password2" placeholder="confirm Password">
                    <small id="confirmHelp" class="invalid-feedback">
                        <!--                        --><?php //echo($errors->first('password')); ?>
                    </small>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
