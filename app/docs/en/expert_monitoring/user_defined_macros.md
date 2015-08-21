[//]: # (Links)
[User Defined Macros]: /macros "User Defined Macros"
[commands]: /commands "Commands"

[//]: # (Pictures)
[add]: /img/docs/expert_monitoring/user_defined_macros/add.gif
[delete]: /img/docs/expert_monitoring/user_defined_macros/delete.gif
[delete macro]: /img/docs/expert_monitoring/user_defined_macros/delete_macro.png

[//]: # (Content)
## What are user defined macros?
On the page [User Defined Macros] you can assign
a value and a description to a predefined name.
Nagios replaces the predefined name with the value you have chosen.

Intended for system paths or specific system command line options,
to make your Nagios configuration more reusable in other system environments.

## How can I create a new macro?
Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> New</a>
in the upper right corner.

A new row will appear with three fields:
1. Displays the macro name.
2. The value that replaces the macro name.
3. Your description for the value you set.

Click on <a class="btn btn-xs btn-primary">Save</a> to make your changes permanent.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

You can add, edit and delete more than one macro at a time.

![add]

##  Where can I use my macros?
In the [commands] section you can use your macros as
command line parameter in each of your commands.

If you are editing or creating a new command you can click on
<a data-toggle="modal" class="btn btn-primary btn-xs"><i class="fa fa-usd"></i> Macros overview</a>
in the upper right corner to view your macros in a pop up window.

## How can I edit a macro?
You click in the value or description field and change the text in the field.

After you have edited your macro, you save your progress.

Click on <a class="btn btn-xs btn-primary">Save</a> to make your changes permanent.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

You can add, edit and delete more than one macro at a time.

## How can I delete a macro?
You click on
<a class="btn btn-default btn-xs txt-color-red"><i class="fa fa-trash-o fa-lg"></i></a>
on the right of the macro you wish to delete.

Click on <a class="btn btn-xs btn-primary">Save</a> to make your changes permanent.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

You can add, edit and delete more than one macro at a time.

![delete]

## How many macros can I create?
You can create up to 256 macros in Nagios.
