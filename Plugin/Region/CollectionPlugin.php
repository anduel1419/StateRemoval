<?php

namespace Anduel\StateRemoval\Plugin\Region;

use Magento\Directory\Model\ResourceModel\Region\Collection;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\ScopeInterface;

class CollectionPlugin
{
    protected ScopeConfigInterface $scopeConfig;
    protected State $appState;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        State $appState
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->appState = $appState;
    }

    /**
     * @throws LocalizedException
     */
    public function beforeLoad(Collection $subject): array
    {
        if (!$subject->isLoaded() && $this->appState->getAreaCode() === Area::AREA_FRONTEND) {
            $hiddenStates = $this->scopeConfig->getValue('hidestate/general/hidden_states', ScopeInterface::SCOPE_STORE);

            if ($hiddenStates) {
                $hiddenStatesArray = explode(',', $hiddenStates);
                $subject->addFieldToFilter('main_table.code', ['nin' => $hiddenStatesArray]);
            }
        }

        return [];
    }
}
