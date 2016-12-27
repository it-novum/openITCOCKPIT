<?php
App::uses('BoostCakeFormHelper', 'BoostCake.View/Helper');
App::uses('View', 'View');

class Contact extends CakeTestModel
{

    /**
     * useTable property
     * @var bool false
     */
    public $useTable = false;

    /**
     * Default schema
     * @var array
     */
    protected $_schema = [
        'id'        => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => '8'],
        'name'      => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'email'     => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'phone'     => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'password'  => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'published' => ['type' => 'date', 'null' => true, 'default' => null, 'length' => null],
        'created'   => ['type' => 'date', 'null' => '1', 'default' => '', 'length' => ''],
        'updated'   => ['type' => 'datetime', 'null' => '1', 'default' => '', 'length' => null],
        'age'       => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => null],
    ];

}

class BoostCakeFormHelperTest extends CakeTestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->View = new View();
        $this->Form = new BoostCakeFormHelper($this->View);

        ClassRegistry::addObject('Contact', new Contact());
    }

    public function tearDown()
    {
        unset($this->View);
        unset($this->Form);
    }

    public function testInput()
    {
        $result = $this->Form->input('name');
        $this->assertTags($result, [
            ['div' => []],
            'label' => ['for' => 'name'],
            'Name',
            '/label',
            ['div' => ['class' => 'input text']],
            ['input' => ['name' => 'data[name]', 'type' => 'text', 'id' => 'name']],
            '/div',
            '/div',
        ]);

        $result = $this->Form->input('name', [
            'div'       => 'row',
            'wrapInput' => 'col col-lg-10',
            'label'     => [
                'class' => 'col col-lg-2 control-label',
            ],
        ]);
        $this->assertTags($result, [
            ['div' => ['class' => 'row']],
            'label' => ['for' => 'name', 'class' => 'col col-lg-2 control-label'],
            'Name',
            '/label',
            ['div' => ['class' => 'col col-lg-10']],
            ['input' => ['name' => 'data[name]', 'type' => 'text', 'id' => 'name']],
            '/div',
            '/div',
        ]);

        $result = $this->Form->input('name', ['div' => false]);
        $this->assertTags($result, [
            'label' => ['for' => 'name'],
            'Name',
            '/label',
            ['div' => ['class' => 'input text']],
            ['input' => ['name' => 'data[name]', 'type' => 'text', 'id' => 'name']],
            '/div',
        ]);

        $result = $this->Form->input('name', ['wrapInput' => false]);
        $this->assertTags($result, [
            ['div' => []],
            'label' => ['for' => 'name'],
            'Name',
            '/label',
            ['input' => ['name' => 'data[name]', 'type' => 'text', 'id' => 'name']],
            '/div',
        ]);

        $result = $this->Form->input('name', [
            'div'       => false,
            'wrapInput' => false,
        ]);
        $this->assertTags($result, [
            'label' => ['for' => 'name'],
            'Name',
            '/label',
            ['input' => ['name' => 'data[name]', 'type' => 'text', 'id' => 'name']],
        ]);
    }

    public function testBeforeInputAfterInput()
    {
        $result = $this->Form->input('name', [
            'beforeInput' => 'Before Input',
            'afterInput'  => 'After Input',
        ]);
        $this->assertTags($result, [
            ['div' => []],
            'label' => ['for' => 'name'],
            'Name',
            '/label',
            ['div' => ['class' => 'input text']],
            'Before Input',
            ['input' => ['name' => 'data[name]', 'type' => 'text', 'id' => 'name']],
            'After Input',
            '/div',
            '/div',
        ]);
    }

    public function testCheckbox()
    {
        $result = $this->Form->input('name', ['type' => 'checkbox']);
        $this->assertTags($result, [
            ['div' => []],
            ['div' => ['class' => 'input checkbox']],
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'hidden', 'name' => 'data[name]', 'id' => 'name_', 'value' => '0']],
            'label' => ['for' => 'name'],
            ['input' => ['name' => 'data[name]', 'type' => 'checkbox', 'value' => '1', 'id' => 'name']],
            ' Name',
            '/label',
            '/div',
            '/div',
            '/div',
        ]);

        $result = $this->Form->input('name', [
            'type'   => 'checkbox',
            'before' => '<label>Name</label>',
            'label'  => false,
        ]);
        $this->assertTags($result, [
            ['div' => []],
            ['label' => []],
            'Name',
            '/label',
            ['div' => ['class' => 'input checkbox']],
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'hidden', 'name' => 'data[name]', 'id' => 'name_', 'value' => '0']],
            ['input' => ['name' => 'data[name]', 'type' => 'checkbox', 'value' => '1', 'id' => 'name']],
            '/div',
            '/div',
            '/div',
        ]);

        $result = $this->Form->input('name', [
            'type'        => 'checkbox',
            'checkboxDiv' => false,
        ]);
        $this->assertTags($result, [
            ['div' => []],
            ['div' => ['class' => 'input checkbox']],
            ['input' => ['type' => 'hidden', 'name' => 'data[name]', 'id' => 'name_', 'value' => '0']],
            'label' => ['for' => 'name'],
            ['input' => ['name' => 'data[name]', 'type' => 'checkbox', 'value' => '1', 'id' => 'name']],
            ' Name',
            '/label',
            '/div',
            '/div',
        ]);
    }

    public function testCheckboxLabelEscape()
    {
        $result = $this->Form->input('name', [
            'type'  => 'checkbox',
            'label' => 'I want $1',
        ]);
        $this->assertTags($result, [
            ['div' => []],
            ['div' => ['class' => 'input checkbox']],
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'hidden', 'name' => 'data[name]', 'id' => 'name_', 'value' => '0']],
            'label' => ['for' => 'name'],
            ['input' => ['name' => 'data[name]', 'type' => 'checkbox', 'value' => '1', 'id' => 'name']],
            ' I want $1',
            '/label',
            '/div',
            '/div',
            '/div',
        ]);
    }

    public function testSelectMultipleCheckbox()
    {
        $result = $this->Form->select('name',
            [
                1 => 'one',
                2 => 'two',
                3 => 'three',
            ],
            [
                'multiple' => 'checkbox',
                'class'    => 'checkbox-inline',
            ]
        );
        $this->assertTags($result, [
            ['input' => ['type' => 'hidden', 'name' => 'data[name]', 'value' => '', 'id' => 'name']],
            ['label' => ['for' => 'Name1', 'class' => 'checkbox-inline']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[name][]', 'value' => '1', 'id' => 'Name1']],
            ' one',
            '/label',
            ['label' => ['for' => 'Name2', 'class' => 'checkbox-inline']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[name][]', 'value' => '2', 'id' => 'Name2']],
            ' two',
            '/label',
            ['label' => ['for' => 'Name3', 'class' => 'checkbox-inline']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[name][]', 'value' => '3', 'id' => 'Name3']],
            ' three',
            '/label',
        ]);

        $result = $this->Form->select('name',
            [
                1 => 'one',
                2 => 'two',
                3 => 'three',
            ],
            [
                'multiple' => 'checkbox',
                'class'    => 'checkbox-inline',
                'value'    => 2,
            ]
        );
        $this->assertTags($result, [
            ['input' => ['type' => 'hidden', 'name' => 'data[name]', 'value' => '', 'id' => 'name']],
            ['label' => ['for' => 'Name1', 'class' => 'checkbox-inline']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[name][]', 'value' => '1', 'id' => 'Name1']],
            ' one',
            '/label',
            ['label' => ['for' => 'Name2', 'class' => 'selected checkbox-inline']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[name][]', 'value' => '2', 'id' => 'Name2', 'checked' => 'checked']],
            ' two',
            '/label',
            ['label' => ['for' => 'Name3', 'class' => 'checkbox-inline']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[name][]', 'value' => '3', 'id' => 'Name3']],
            ' three',
            '/label',
        ]);

        $result = $this->Form->select('name',
            [
                1       => 'bill',
                'Smith' => [
                    2 => 'fred',
                    3 => 'fred jr.',
                ],
            ],
            [
                'multiple' => 'checkbox',
                'class'    => 'checkbox-inline',
            ]
        );
        $this->assertTags($result, [
            ['input' => ['type' => 'hidden', 'name' => 'data[name]', 'value' => '', 'id' => 'name']],
            ['label' => ['for' => 'Name1', 'class' => 'checkbox-inline']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[name][]', 'value' => '1', 'id' => 'Name1']],
            ' bill',
            '/label',
            'fieldset' => [],
            'legend'   => [],
            'Smith',
            '/legend',
            ['label' => ['for' => 'Name2', 'class' => 'checkbox-inline']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[name][]', 'value' => '2', 'id' => 'Name2']],
            ' fred',
            '/label',
            ['label' => ['for' => 'Name3', 'class' => 'checkbox-inline']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[name][]', 'value' => '3', 'id' => 'Name3']],
            ' fred jr.',
            '/label',
            '/fieldset',
        ]);
    }

    public function testRadio()
    {
        $result = $this->Form->input('name', [
            'type'    => 'radio',
            'options' => [
                'one' => 'This is one',
                'two' => 'This is two',
            ],
        ]);
        $this->assertTags($result, [
            ['div' => []],
            ['div' => ['class' => 'input radio']],
            'fieldset' => [],
            'legend'   => [],
            'Name',
            '/legend',
            ['input' => ['type' => 'hidden', 'name' => 'data[name]', 'id' => 'name_', 'value' => '']],
            ['label' => ['for' => 'nameOne', 'class' => 'radio']],
            ['input' => ['name' => 'data[name]', 'type' => 'radio', 'value' => 'one', 'id' => 'nameOne']],
            ' This is one',
            '/label',
            ['label' => ['for' => 'nameTwo', 'class' => 'radio']],
            ['input' => ['name' => 'data[name]', 'type' => 'radio', 'value' => 'two', 'id' => 'nameTwo']],
            ' This is two',
            '/label',
            '/fieldset',
            '/div',
            '/div',
        ]);
    }

    public function testErrorMessage()
    {
        $Contact = ClassRegistry::getObject('Contact');
        $Contact->validationErrors['password'] = ['Please provide a password'];

        $result = $this->Form->input('Contact.password', [
            'div'   => 'row',
            'label' => [
                'class' => 'col col-lg-2 control-label',
            ],
            'class' => 'input-with-feedback',
        ]);
        $this->assertTags($result, [
            ['div' => ['class' => 'row has-error error']],
            'label' => ['for' => 'ContactPassword', 'class' => 'col col-lg-2 control-label'],
            'Password',
            '/label',
            ['div' => ['class' => 'input password']],
            'input' => [
                'type' => 'password', 'name' => 'data[Contact][password]',
                'id'   => 'ContactPassword', 'class' => 'input-with-feedback form-error',
            ],
            ['span' => ['class' => 'help-block text-danger']],
            'Please provide a password',
            '/span',
            '/div',
            '/div',
        ]);

        $result = $this->Form->input('Contact.password', [
            'div'          => 'row',
            'label'        => [
                'class' => 'col col-lg-2 control-label',
            ],
            'class'        => 'input-with-feedback',
            'errorMessage' => false,
        ]);
        $this->assertTags($result, [
            ['div' => ['class' => 'row has-error error']],
            'label' => ['for' => 'ContactPassword', 'class' => 'col col-lg-2 control-label'],
            'Password',
            '/label',
            ['div' => ['class' => 'input password']],
            'input' => [
                'type' => 'password', 'name' => 'data[Contact][password]',
                'id'   => 'ContactPassword', 'class' => 'input-with-feedback form-error',
            ],
            '/div',
            '/div',
        ]);

        $result = $this->Form->input('Contact.password', [
            'div'         => 'control-group',
            'label'       => [
                'class' => 'control-label',
            ],
            'wrapInput'   => 'controls',
            'beforeInput' => '<div class="input-append">',
            'afterInput'  => '<span class="add-on">AddOn</span></div>',
        ]);
        $this->assertTags($result, [
            ['div' => ['class' => 'control-group has-error error']],
            'label' => ['for' => 'ContactPassword', 'class' => 'control-label'],
            'Password',
            '/label',
            ['div' => ['class' => 'controls']],
            ['div' => ['class' => 'input-append']],
            'input' => [
                'type' => 'password', 'name' => 'data[Contact][password]',
                'id'   => 'ContactPassword', 'class' => 'form-error',
            ],
            ['span' => ['class' => 'add-on']],
            'AddOn',
            '/span',
            '/div',
            ['span' => ['class' => 'help-block text-danger']],
            'Please provide a password',
            '/span',
            '/div',
            '/div',
        ]);
    }

    public function testPostLink()
    {
        $result = $this->Form->postLink('Delete', '/posts/delete/1', [
            'block' => 'form',
        ]);
        $this->assertTags($result, [
            'a' => ['href' => '#', 'onclick' => 'preg:/document\.post_\w+\.submit\(\); event\.returnValue = false; return false;/'],
            'Delete',
            '/a',
        ]);

        $result = $this->View->fetch('form');
        $this->assertTags($result, [
            'form'  => [
                'method' => 'post', 'action' => '/posts/delete/1',
                'name'   => 'preg:/post_\w+/', 'id' => 'preg:/post_\w+/', 'style' => 'display:none;',
            ],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/form',
        ]);
    }

}
