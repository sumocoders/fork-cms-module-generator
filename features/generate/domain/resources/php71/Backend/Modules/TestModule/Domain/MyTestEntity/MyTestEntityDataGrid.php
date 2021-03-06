<?php

namespace Backend\Modules\TestModule\Domain\MyTestEntity;

use Backend\Core\Engine\DataGridDatabase;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Model;
use Backend\Core\Language\Language;
use Backend\Core\Language\Locale;

class MyTestEntityDataGrid extends DataGridDatabase
{
    public function __construct(Locale $locale)
    {
        parent::__construct(
            'SELECT i.id, i.title
             FROM MyTestEntityTable AS i
             WHERE i.locale = :locale',
            ['locale' => $locale]
        );

        $this->setSortingColumns(['title']);

        if (BackendAuthentication::isAllowedAction('MyTestEntityEdit')) {
            $editUrl = Model::createUrlForAction('MyTestEntityEdit', null, null, ['id' => '[id]'], false);
            $this->setColumnURL('title', $editUrl);
            $this->addColumn('edit', null, Language::lbl('Edit'), $editUrl, Language::lbl('Edit'));
        }
    }

    public static function getHtml(Locale $locale): string
    {
        return (new self($locale))->getContent();
    }
}
