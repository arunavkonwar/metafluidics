# metafluidics

![MIT News](http://news.mit.edu/sites/mit.edu.newsoffice/files/images/2017/MIT-Open-Micro_0.jpg)

## Useful links

Staging

* [Site](http://metafluidics.staging.wpengine.com/)
* Once you have logged into the site with an administrator account, you can
  access the [WP Admin](http://metafluidics.staging.wpengine.com/wp-admin/).

Production

* [Site](http://metafluidics.wpengine.com/)
* Once you have logged into the site with an administrator account, you can
  access the [WP Admin](http://metafluidics.wpengine.com/wp-admin/).

Accessing Staging and Production (set in [WP Engine Utilities](https://my.wpengine.com/installs/metafluidics/utilities))

* username: metafluidics
* password: bocoup

WP Engine Admin

* [User Portal](https://my.wpengine.com/)
* [phpMyAdmin](https://my.wpengine.com/installs/metafluidics/phpmyadmin)

### User Roles

* An "Administrator" has full access to the WP Admin and may add or remove
"User" and "Administrator" accounts.
* The currently logged-in Administrator may not remove their own account.

#### Adding an Administrator

In the WP Admin, choose **Users** in the left nav.

1. Click **Add New** under Users in the left nav.
1. Enter all relevant information.
1. Select the **Role** of **Administrator**.
1. Click **Add New User** when done.

#### Modifying a User

In the WP Admin, choose **Users** in the left nav.

1. Click the name of the User in the list or hover over the name of the
   User, then click **Edit**.
1. Modify User data.
1. Click **Update Profile** when done.

#### Removing a User

In the WP Admin, choose **Users** in the left nav.

1. Hover over the name of the User, then click **Delete**.
1. On the next page, ensure **Delete all content** is selected.
1. Click **Confirm Deletion** when done.

## Deployment

The following instructions are based on the [Deploy your WP Engine hosted
application with the ease of Git](http://wpengine.com/git/) guide. If you have
any questions about this process that aren't covered here, be sure to check the
_Deploying to Production_, _Deploying to Staging_, and _FAQs_ sections of that
guide.

Also note that in the following examples, the `$` is used to simulate a `bash`
shell prompt. Don't type in the `$`.

### Add your SSH key to WP Engine

Adding an SSH key to WP Engine should only need to be done once per user, per
site. The presence of an SSH key is what allows a user to deploy to WP Engine.

If you don't already have a public key, create one by following the GitHub
[Generating SSH keys](https://help.github.com/articles/generating-ssh-keys/)
guide.

Ensure your public key has been added to the [WP Engine fpdashboard "Git Push"
page](https://my.wpengine.com/installs/metafluidics/git_push), following the
instructions therein.

### Add Git remotes

Adding Git remotes should only need to be done once per repository clone. Once
these remotes have been added, pushing to staging and production becomes
possible.

Add this site's staging and production Git remotes by running this command:

```bash
$ ./deploy/setup-remotes.sh
```

You can verify that the Git remotes were added like so:

```bash
$ git remote -v
origin	https://github.com/bocoup/ll-metafluidics-site.git (fetch)
origin	https://github.com/bocoup/ll-metafluidics-site.git (push)
production	git@git.wpengine.com:production/metafluidics.git (fetch)
production	git@git.wpengine.com:production/metafluidics.git (push)
staging	git@git.wpengine.com:staging/metafluidics.git (fetch)
staging	git@git.wpengine.com:staging/metafluidics.git (push)
```

### Deploy to staging

Deploying the `master` branch to staging:

```bash
$ git push staging master
```

Deploying the `localbranchname` feature branch to staging:

```bash
$ git push staging localbranchname:master
```

### Deploy to production

Deploying the `master` branch to production:

```bash
$ git push production master
```

## Managing the database

<https://my.wpengine.com/installs/metafluidics/phpmyadmin>

* Staging database: `snapshot_metafluidics`
* Production database: `wp_metafluidics`

## Development

Development of the site should proceed like so:

1. Run `vagrant up`
2. Browse to <http://metafluidics.loc/wp-admin.php>
3. Log into the wp-admin with the credentials: `admin` / `admin`
4. Create a feature branch.
5. Make changes to files in [wp-content](wp-content).
6. Run `vagrant ssh -c 'sudo service php5-fpm restart'`
7. Refresh the browser to see your changes.
8. Repeat steps 5-7 until done, committing changes when appropriate.
9. Push your branch to `staging` and test the staging site.
10. Repeat steps 5-9 until done.
11. Merge your feature branch into `master`.
12. Push `master` to `origin`.
15. Run `vagrant destroy` to stop and delete the Vagrant box.

### Dependencies

The following will need to be installed on your local development machine before
you can use this workflow. All versions should be the latest available, as some
required features may not be available in older versions.

* **[Ansible](http://docs.ansible.com/) 1.9.2**
  - Install `ansible` via apt (Ubuntu), yum (Fedora), [homebrew][homebrew] (OS
    X), etc. See the [Ansible installation
    instructions](http://docs.ansible.com/intro_installation.html) for detailed,
    platform-specific information.
* **[VirtualBox](https://www.virtualbox.org/)**
  - [Download](https://www.virtualbox.org/wiki/Downloads) (All platforms)
  - Install `virtualbox` via [homebrew cask][cask] (OS X)
* **[Vagrant](https://www.vagrantup.com/)**
  - [Download](http://docs.vagrantup.com/v2/installation/) (All platforms)
  - Install `vagrant` via [homebrew cask][cask] (OS X)
* **[vagrant-hostsupdater](https://github.com/cogitatio/vagrant-hostsupdater)**
  - Install with `vagrant plugin install vagrant-hostsupdater` (All platforms)

[homebrew]: http://brew.sh/
[cask]: http://caskroom.io/

### Running specific Ansible playbooks

#### Configuration

* [deploy/ansible/group_vars/all.yml][all] - Global variables. These settings
  are available to all playbooks and roles.

[all]: deploy/ansible/group_vars/all.yml

#### Playbooks and Roles:

* [provision](deploy/ansible/provision.yml) - Install all dependencies required
  to build the base box.
  * [base](deploy/ansible/roles/base) role - Install Apt packages.
* [configure](deploy/ansible/configure.yml) - Configure the box and get services
  set up. _The following roles may be run individually via a role-named tag, eg.
  `--tags=wordpress`:_
  * [configure](deploy/ansible/roles/configure) role - Basic server
    configuration.
  * [users](deploy/ansible/roles/users) role - Enable user account for the
    currently logged-in user so that playbooks may be run manually via
    `run-playbook.sh` or `ansible-playbook`.
  * [nginx](deploy/ansible/roles/nginx) role - Enable SSL (if specified) and
    configure nginx.
  * [php](deploy/ansible/roles/php) role - Update php configuration.
  * [mysql](deploy/ansible/roles/mysql) role - Harden MySQL installation.
  * [wordpress](deploy/ansible/roles/wordpress) role - Download and install
    WordPress `wp_version` and set up its configuration, linking the
    [wp-content](wp-content) directory. Use the `wp_force=true` extra var to
    force the currently-installed version of WordPress to be reinstalled.
  * [postfix](deploy/ansible/roles/postfix) role - Configure the SMTP email
    server.
* [db-dump](deploy/ansible/db-dump.yml) - Dump `wp_db_name` database to db-named
  file in `wp-database`.
* [db-restore](deploy/ansible/db-restore.yml) - Restore `wp_db_name` database
  from db-named file in `wp-database`.
* [init](deploy/ansible/init.yml) - Run `provision`, `configure` and
  `db-restore` playbooks. Runs on `vagrant up`.

#### Examples:

```bash
# Run all configure playbook roles:

$ ./deploy/run-playbook.sh configure vagrant


# Run configure playbook role tagged "wordpress":
# (valid tags are: configure, mysql, nginx, php, users, wordpress, postfix)

$ ./deploy/run-playbook.sh configure vagrant --tags=wordpress
```
