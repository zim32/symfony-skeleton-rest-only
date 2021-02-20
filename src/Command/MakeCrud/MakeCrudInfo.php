<?php

namespace App\Command\MakeCrud;


class MakeCrudInfo
{

    protected $resourceSingularName;
    protected $resourcePluralName;
    protected $entityFQNC;
    protected $entityShortName;
    protected $resourceTag;

    protected $getItemsOperationUrl;
    protected $getItemsOperationRouteName;
    protected $getItemsOperationMethodName;
    protected $getItemsOperationResponseDescription;

    protected $getItemOperationRouteName;
    protected $getItemOperationMethodName;
    protected $getItemOperationResponseDescription;

    protected $postItemOperationRouteName;
    protected $postItemOperationMethodName;
    protected $postItemOperationResponseDescription;
    protected $postItemOperationFormType;

    protected $patchItemOperationRouteName;
    protected $patchItemOperationMethodName;
    protected $patchItemOperationResponseDescription;
    protected $patchItemOperationFormType;

    protected $deleteItemOperationRouteName;
    protected $deleteItemOperationMethodName;
    protected $deleteItemOperationResponseDescription;

    protected $frontendPluginDirName;

    protected $frontendListPageRouteName;
    protected $frontendListPageUrl;
    protected $frontendListPageTitle;
    protected $frontendListPageAddResourceText;

    protected $frontendEditPageRouteName;
    protected $frontendEditPageUrl;
    protected $frontendEditPageTitle = '';

    protected $frontendAddPageRouteName;
    protected $frontendAddPageUrl;
    protected $frontendAddPageTitle;

    /**
     * @return mixed
     */
    public function getResourceSingularName()
    {
        return $this->resourceSingularName;
    }

    /**
     * @param mixed $resourceSingularName
     */
    public function setResourceSingularName($resourceSingularName)
    {
        $this->resourceSingularName = $resourceSingularName;
    }

    /**
     * @return mixed
     */
    public function getResourcePluralName()
    {
        return $this->resourcePluralName;
    }

    /**
     * @param mixed $resourcePluralName
     */
    public function setResourcePluralName($resourcePluralName)
    {
        $this->resourcePluralName = $resourcePluralName;
    }

    /**
     * @return mixed
     */
    public function getGetItemsOperationUrl()
    {
        return $this->getItemsOperationUrl;
    }

    /**
     * @param mixed $getItemsOperationUrl
     */
    public function setGetItemsOperationUrl($getItemsOperationUrl)
    {
        $this->getItemsOperationUrl = $getItemsOperationUrl;
    }

    /**
     * @return mixed
     */
    public function getGetItemsOperationRouteName()
    {
        return $this->getItemsOperationRouteName;
    }

    /**
     * @param mixed $getItemsOperationRouteName
     */
    public function setGetItemsOperationRouteName($getItemsOperationRouteName)
    {
        $this->getItemsOperationRouteName = $getItemsOperationRouteName;
    }

    /**
     * @return mixed
     */
    public function getGetItemsOperationMethodName()
    {
        return $this->getItemsOperationMethodName;
    }

    /**
     * @param mixed $getItemsOperationMethodName
     */
    public function setGetItemsOperationMethodName($getItemsOperationMethodName)
    {
        $this->getItemsOperationMethodName = $getItemsOperationMethodName;
    }

    /**
     * @return mixed
     */
    public function getGetItemsOperationResponseDescription()
    {
        return $this->getItemsOperationResponseDescription;
    }

    /**
     * @param mixed $getItemsOperationResponseDescription
     */
    public function setGetItemsOperationResponseDescription($getItemsOperationResponseDescription)
    {
        $this->getItemsOperationResponseDescription = $getItemsOperationResponseDescription;
    }

    /**
     * @return mixed
     */
    public function getEntityFQNC()
    {
        return $this->entityFQNC;
    }

    /**
     * @param mixed $entityFQNC
     */
    public function setEntityFQNC($entityFQNC)
    {
        $this->entityFQNC = $entityFQNC;
    }

    /**
     * @return mixed
     */
    public function getEntityShortName()
    {
        return $this->entityShortName;
    }

    /**
     * @param mixed $entityShortName
     */
    public function setEntityShortName($entityShortName)
    {
        $this->entityShortName = $entityShortName;
    }

    /**
     * @return mixed
     */
    public function getGetItemOperationRouteName()
    {
        return $this->getItemOperationRouteName;
    }

    /**
     * @param mixed $getItemOperationRouteName
     */
    public function setGetItemOperationRouteName($getItemOperationRouteName)
    {
        $this->getItemOperationRouteName = $getItemOperationRouteName;
    }

    /**
     * @return mixed
     */
    public function getGetItemOperationMethodName()
    {
        return $this->getItemOperationMethodName;
    }

    /**
     * @param mixed $getItemOperationMethodName
     */
    public function setGetItemOperationMethodName($getItemOperationMethodName)
    {
        $this->getItemOperationMethodName = $getItemOperationMethodName;
    }

    /**
     * @return mixed
     */
    public function getGetItemOperationResponseDescription()
    {
        return $this->getItemOperationResponseDescription;
    }

    /**
     * @param mixed $getItemOperationResponseDescription
     */
    public function setGetItemOperationResponseDescription($getItemOperationResponseDescription)
    {
        $this->getItemOperationResponseDescription = $getItemOperationResponseDescription;
    }

    /**
     * @return mixed
     */
    public function getPostItemOperationRouteName()
    {
        return $this->postItemOperationRouteName;
    }

    /**
     * @param mixed $postItemOperationRouteName
     */
    public function setPostItemOperationRouteName($postItemOperationRouteName)
    {
        $this->postItemOperationRouteName = $postItemOperationRouteName;
    }

    /**
     * @return mixed
     */
    public function getPostItemOperationMethodName()
    {
        return $this->postItemOperationMethodName;
    }

    /**
     * @param mixed $postItemOperationMethodName
     */
    public function setPostItemOperationMethodName($postItemOperationMethodName)
    {
        $this->postItemOperationMethodName = $postItemOperationMethodName;
    }

    /**
     * @return mixed
     */
    public function getPostItemOperationResponseDescription()
    {
        return $this->postItemOperationResponseDescription;
    }

    /**
     * @param mixed $postItemOperationResponseDescription
     */
    public function setPostItemOperationResponseDescription($postItemOperationResponseDescription)
    {
        $this->postItemOperationResponseDescription = $postItemOperationResponseDescription;
    }

    /**
     * @return mixed
     */
    public function getPostItemOperationFormType()
    {
        return $this->postItemOperationFormType;
    }

    /**
     * @param mixed $postItemOperationFormType
     */
    public function setPostItemOperationFormType($postItemOperationFormType)
    {
        $this->postItemOperationFormType = $postItemOperationFormType;
    }

    /**
     * @return mixed
     */
    public function getResourceTag()
    {
        return $this->resourceTag;
    }

    /**
     * @param mixed $resourceTag
     */
    public function setResourceTag($resourceTag)
    {
        $this->resourceTag = $resourceTag;
    }

    /**
     * @return mixed
     */
    public function getPatchItemOperationRouteName()
    {
        return $this->patchItemOperationRouteName;
    }

    /**
     * @param mixed $patchItemOperationRouteName
     */
    public function setPatchItemOperationRouteName($patchItemOperationRouteName)
    {
        $this->patchItemOperationRouteName = $patchItemOperationRouteName;
    }

    /**
     * @return mixed
     */
    public function getPatchItemOperationMethodName()
    {
        return $this->patchItemOperationMethodName;
    }

    /**
     * @param mixed $patchItemOperationMethodName
     */
    public function setPatchItemOperationMethodName($patchItemOperationMethodName)
    {
        $this->patchItemOperationMethodName = $patchItemOperationMethodName;
    }

    /**
     * @return mixed
     */
    public function getPatchItemOperationResponseDescription()
    {
        return $this->patchItemOperationResponseDescription;
    }

    /**
     * @param mixed $patchItemOperationResponseDescription
     */
    public function setPatchItemOperationResponseDescription($patchItemOperationResponseDescription)
    {
        $this->patchItemOperationResponseDescription = $patchItemOperationResponseDescription;
    }

    /**
     * @return mixed
     */
    public function getPatchItemOperationFormType()
    {
        return $this->patchItemOperationFormType;
    }

    /**
     * @param mixed $patchItemOperationFormType
     */
    public function setPatchItemOperationFormType($patchItemOperationFormType)
    {
        $this->patchItemOperationFormType = $patchItemOperationFormType;
    }

    /**
     * @return mixed
     */
    public function getFrontendPluginDirName()
    {
        return $this->frontendPluginDirName;
    }

    /**
     * @param mixed $frontendPluginDirName
     */
    public function setFrontendPluginDirName($frontendPluginDirName)
    {
        $this->frontendPluginDirName = $frontendPluginDirName;
    }

    /**
     * @return mixed
     */
    public function getFrontendListPageTitle()
    {
        return $this->frontendListPageTitle;
    }

    /**
     * @param mixed $frontendListPageTitle
     */
    public function setFrontendListPageTitle($frontendListPageTitle)
    {
        $this->frontendListPageTitle = $frontendListPageTitle;
    }

    /**
     * @return mixed
     */
    public function getFrontendEditPageTitle()
    {
        return $this->frontendEditPageTitle;
    }

    /**
     * @param mixed $frontendEditPageTitle
     */
    public function setFrontendEditPageTitle($frontendEditPageTitle)
    {
        $this->frontendEditPageTitle = $frontendEditPageTitle;
    }

    /**
     * @return mixed
     */
    public function getFrontendAddPageTitle()
    {
        return $this->frontendAddPageTitle;
    }

    /**
     * @param mixed $frontendAddPageTitle
     */
    public function setFrontendAddPageTitle($frontendAddPageTitle)
    {
        $this->frontendAddPageTitle = $frontendAddPageTitle;
    }

    /**
     * @return mixed
     */
    public function getFrontendListPageRouteName()
    {
        return $this->frontendListPageRouteName;
    }

    /**
     * @param mixed $frontendListPageRouteName
     */
    public function setFrontendListPageRouteName($frontendListPageRouteName)
    {
        $this->frontendListPageRouteName = $frontendListPageRouteName;
    }

    /**
     * @return mixed
     */
    public function getFrontendListPageUrl()
    {
        return $this->frontendListPageUrl;
    }

    /**
     * @param mixed $frontendListPageUrl
     */
    public function setFrontendListPageUrl($frontendListPageUrl)
    {
        $this->frontendListPageUrl = $frontendListPageUrl;
    }

    /**
     * @return mixed
     */
    public function getFrontendEditPageRouteName()
    {
        return $this->frontendEditPageRouteName;
    }

    /**
     * @param mixed $frontendEditPageRouteName
     */
    public function setFrontendEditPageRouteName($frontendEditPageRouteName)
    {
        $this->frontendEditPageRouteName = $frontendEditPageRouteName;
    }

    /**
     * @return mixed
     */
    public function getFrontendEditPageUrl()
    {
        return $this->frontendEditPageUrl;
    }

    /**
     * @param mixed $frontendEditPageUrl
     */
    public function setFrontendEditPageUrl($frontendEditPageUrl)
    {
        $this->frontendEditPageUrl = $frontendEditPageUrl;
    }

    /**
     * @return mixed
     */
    public function getFrontendAddPageRouteName()
    {
        return $this->frontendAddPageRouteName;
    }

    /**
     * @param mixed $frontendAddPageRouteName
     */
    public function setFrontendAddPageRouteName($frontendAddPageRouteName)
    {
        $this->frontendAddPageRouteName = $frontendAddPageRouteName;
    }

    /**
     * @return mixed
     */
    public function getFrontendAddPageUrl()
    {
        return $this->frontendAddPageUrl;
    }

    /**
     * @param mixed $frontendAddPageUrl
     */
    public function setFrontendAddPageUrl($frontendAddPageUrl)
    {
        $this->frontendAddPageUrl = $frontendAddPageUrl;
    }

    /**
     * @return mixed
     */
    public function getFrontendListPageAddResourceText()
    {
        return $this->frontendListPageAddResourceText;
    }

    /**
     * @param mixed $frontendListPageAddResourceText
     */
    public function setFrontendListPageAddResourceText($frontendListPageAddResourceText)
    {
        $this->frontendListPageAddResourceText = $frontendListPageAddResourceText;
    }

    /**
     * @return mixed
     */
    public function getDeleteItemOperationRouteName()
    {
        return $this->deleteItemOperationRouteName;
    }

    /**
     * @param mixed $deleteItemOperationRouteName
     */
    public function setDeleteItemOperationRouteName($deleteItemOperationRouteName)
    {
        $this->deleteItemOperationRouteName = $deleteItemOperationRouteName;
    }

    /**
     * @return mixed
     */
    public function getDeleteItemOperationMethodName()
    {
        return $this->deleteItemOperationMethodName;
    }

    /**
     * @param mixed $deleteItemOperationMethodName
     */
    public function setDeleteItemOperationMethodName($deleteItemOperationMethodName)
    {
        $this->deleteItemOperationMethodName = $deleteItemOperationMethodName;
    }

    /**
     * @return mixed
     */
    public function getDeleteItemOperationResponseDescription()
    {
        return $this->deleteItemOperationResponseDescription;
    }

    /**
     * @param mixed $deleteItemOperationResponseDescription
     */
    public function setDeleteItemOperationResponseDescription($deleteItemOperationResponseDescription)
    {
        $this->deleteItemOperationResponseDescription = $deleteItemOperationResponseDescription;
    }
}