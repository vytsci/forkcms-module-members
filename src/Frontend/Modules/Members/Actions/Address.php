<?php

namespace Frontend\Modules\Members\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Core\Engine\Form as FrontendForm;
use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\Members\Engine\Authentication as FrontendMembersAuthentication;
use Frontend\Modules\Members\Engine\Helper as FrontendMembersHelper;

use Common\Modules\Members\Engine\Helper as CommonMembersHelper;
use Common\Modules\Members\Entity\Member;

class Address extends FrontendBaseBlock
{

    /**
     * @var Member
     */
    private $member;

    /**
     * @var \Common\Modules\Members\Entity\Address
     */
    private $address;

    /**
     * The form instance
     *
     * @var FrontendForm
     */
    protected $frm;

    /**
     * @throws \Common\Exception\RedirectException
     */
    public function execute()
    {
        if (!FrontendMembersAuthentication::isLoggedIn()) {
            $this->redirect(
                FrontendNavigation::getURLForBlock('Profiles', 'Login')
                .CommonMembersHelper::getLoginQueryString(),
                307
            );
        }

        parent::execute();

        $this->member = new Member(array(FrontendMembersAuthentication::getProfile()->getId()));

        $id = $this->URL->getParameter(0, 'int');
        $parameters = array();
        if (isset($id)) {
            $parameters[] = $id;
        }
        $this->address = new \Common\Modules\Members\Entity\Address($parameters);

        $this->loadTemplate();

        $this->loadForm();
        $this->validateForm();

        $this->parse();
    }

    /**
     * @throws \Frontend\Core\Engine\Exception
     */
    private function loadForm()
    {
        $this->frm = new FrontendForm('address');

        CommonMembersHelper::parseFieldsAddress($this->frm, FRONTEND_LANGUAGE, true, $this->address);
        FrontendMembersHelper::loadAssetsAddress($this->header);
    }

    /**
     * @throws \Common\Exception\RedirectException
     * @throws \Exception
     */
    private function validateForm()
    {
        if ($this->frm->isSubmitted()) {
            $this->frm->cleanupFields();
            CommonMembersHelper::validateAddress($this->frm, $this->address);

            if ($this->frm->isCorrect()) {
                $this->address
                    ->setMemberId($this->member->getId())
                    ->save();

                FrontendModel::triggerEvent(
                    $this->getModule(),
                    'after_add_address',
                    array('item' => $this->address->toArray())
                );

                $this->redirect(FrontendNavigation::getURLForBlock('Members', 'Account'));
            }
        }
    }

    protected function parse()
    {
        $this->frm->parse($this->tpl);
    }
}
