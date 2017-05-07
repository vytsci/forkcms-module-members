<?php

namespace Frontend\Modules\Members\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Core\Engine\Navigation;
use Frontend\Modules\Members\Engine\Model as FrontendMembersModel;

/**
 * Class Index
 * @package Frontend\Modules\Members\Actions
 */
class Index extends FrontendBaseBlock
{

    /**
     * @var array
     */
    private $members = array();

    /**
     * The pagination array
     * It will hold all needed parameters, some of them need initialization.
     *
     * @var    array
     */
    protected $pagination = array(
        'limit' => 10,
        'offset' => 0,
        'requested_page' => 1,
        'num_items' => null,
        'num_pages' => null,
    );

    /**
     *
     */
    public function execute()
    {
        if ($this->loadDetail()) {
            return;
        }

        if (!$this->get('fork.settings')->get('Members', 'enable_index_page', 0)) {
            $this->redirect(Navigation::getURLForBlock('Members', 'Account'));
        }

        parent::execute();

        $this->loadTemplate();
        $this->getData();
        $this->parse();
    }

    /**
     * @return bool
     */
    private function loadDetail()
    {
        $detail = new Detail($this->getKernel(), $this->getModule(), 'Detail');
        if ($detail->hasRecord()) {
            $detail->execute();
            $this->tpl = $detail->getTemplate();
            $this->setTemplatePath($detail->getTemplatePath());

            return true;
        }

        return false;
    }

    /**
     * Load the data, don't forget to validate the incoming data
     */
    private function getData()
    {
        $requestedPage = $this->URL->getParameter('page', 'int', 1);

        $this->pagination['url'] = Navigation::getURLForBlock('Events');
        $this->pagination['limit'] = $this->get('fork.settings')->get('Members', 'members_per_page', 10);

        $this->pagination['num_items'] = FrontendMembersModel::getCountMembers();
        $this->pagination['num_pages'] = (int)ceil($this->pagination['num_items'] / $this->pagination['limit']);

        if ($this->pagination['num_pages'] == 0) {
            $this->pagination['num_pages'] = 1;
        }

        if ($requestedPage > $this->pagination['num_pages'] || $requestedPage < 1) {
            $this->redirect(Navigation::getURL(404));
        }

        $this->pagination['requested_page'] = $requestedPage;
        $this->pagination['offset'] =
            ($this->pagination['requested_page'] * $this->pagination['limit']) - $this->pagination['limit'];

        $this->members = FrontendMembersModel::getListMembers(
            null,
            null,
            $this->pagination['offset'].', '.$this->pagination['limit']
        );
    }

    /**
     *
     */
    private function parse()
    {
        $this->tpl->assign('members', $this->members);

        $this->parsePagination();
    }
}
