[//]: # (Links)
[Commands]: /commands "commands"
[configure]: #configure "Configure your commands"
[types]: #types "Command types explanation"
[adding]: /commands/add (add a new command)

[//]: # (Pictures)

[//]: # (Content)

## What are commands?

[Commands] combine check, host check, notification or event handler commands.
A command executes as a nagios terminal command from the user nagios on your server.
The default working directory will be $path_to_openITCOCKPIT/nagios/libexec.
Only create a command if you know what you are doing.

## How can I create a new command?

Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> New</a>
in the upper right corner.

On the [page][adding] that appears you can configure your command.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new command.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I edit a command?

Click on
<i class="fa fa-gear fa-lg txt-color-teal list-edit"></i>
in the same row as the command you want to edit.

On the page that appears you can reconfigure your command.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new command.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I delete a command?

Click on
<i class="fa fa-gear fa-lg txt-color-teal list-edit"></i>
in the same row as the command you want to delete.

On the page that appears, where you can also reconfigure your command,
you can delete it by clicking on
<a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</a>.
A window will pop up asking if you really want to delete it,
confirm to delete.

## What different command types are there? <span id="types"></span>

There are four different command types.

A **check command** executes to check a service in a specific interval.
All check commands listed under the
**<i class="fa fa-lg fa-code"></i> Commands**
tab.

A **check host command** executes to check a host in a specific interval.
All host check commands listed under the
**<i class="fa fa-lg fa-code"></i> Host checks**
tab.

The **notification command** executes when nagios sends a notification.
All notification commands listed under the
**<i class="fa fa-lg fa-envelope-o"></i> Notifications**
tab.

An **event handler command** executes whenever a state change occurs.
All event handler commands listed under the
**<i class="fa fa-lg fa-code-fork"></i> Event handler**
tab.

## How do I configure a command? <span id="configure"></span>

**Command type** - Choose which [type][types] of command it is.

**Name** - Choose a reasonable name. (required)

**Command line** - The command that executes.
If you use arguments, use macro names for them.

**Description** - Describe the command.

If your command needs **arguments**, you can add them.
Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add argument</a>
and the name of the argument and a text field appear where you can set your argument.
At execution the argument text replaces **$ARG?$**.
If you want to delete an argument, click on
<a class="btn btn-default btn-xs txt-color-red"><i class="fa fa-trash-o"></i></a>.

Below the arguments section you will find a terminal emulation connected to your server where you can try your out a nagios command.
It is a full featured nagios terminal, which uses all available nagios commands on your server.
The terminal does not know the arguments you have set up above and does not know your macros.
