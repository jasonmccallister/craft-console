<?php

namespace mccallister\console\transformers;

use Craft;
use craft\elements\Asset;
use League\Fractal\TransformerAbstract;

class AssetTransformer extends TransformerAbstract
{
    public function transform(Asset $element)
    {
        return [
            'id' => (int) $element->id,
            'uid' => $element->uid,
            'folderId' => (int) $element->folderId,
            'folderPath' => $element->folderPath,
            'filename' => $element->filename,
            'kind' => $element->kind,
            'size' => $element->size,
            'keptFile' => $element->keptFile,
            'newLocation' => $element->newLocation,
            'locationError' => $element->locationError,
            'newFilename' => $element->newFilename,
            'newFolderId' => (int) $element->newFolderId,
            'tempFilePath' => $element->tempFilePath,
            'avoidFilenameConflicts' => $element->avoidFilenameConflicts,
            'suggestedFilename' => $element->suggestedFilename,
            'conflictingFilename' => $element->conflictingFilename,
            'deletedWithVolume' => $element->deletedWithVolume,
            'keepFileOnDelete' => $element->keepFileOnDelete,
            'tempId' => $element->tempId,
            'draftId' => $element->draftId,
            'revisionId' => $element->revisionId,
            'fieldLayoutId' => $element->fieldLayoutId,
            'contentId' => $element->contentId,
            'enabled' => $element->enabled,
            'archived' => $element->archived,
            'siteId' => $element->siteId,
            'enabledForSite' => $element->enabledForSite,
            'title' => $element->title,
            'slug' => $element->slug,
            'uri' => $element->uri,
            'trashed' => $element->trashed,
            'propagateAll' => $element->propagateAll,
            'newSiteIds' => $element->newSiteIds,
            'resaving' => $element->resaving,
            'duplicateOf' => $element->duplicateOf,
            'previewing' => $element->previewing,
            'hardDelete' => $element->hardDelete,
            'ref' => $element->ref,
            'status' => $element->status,
            'structureId' => $element->structureId,
            'url' => $element->url,
            'extension' => $element->extension,
            'focalPoint' => $element->focalPoint,
            'hasFocalPoint' => $element->hasFocalPoint,
            'height' => $element->height,
            'mimeType' => $element->mimeType,
            'path' => $element->path,
            'width' => $element->width,
            'meta' => $this->getMeta($element),
            'links' => $this->getLinks($element),
        ];
    }

    protected function getMeta(Asset $asset): array
    {
        return  [
            'dateCreated' => $asset->dateCreated,
            'dateModified' => $asset->dateModified,
            'dateDeleted' => $asset->dateDeleted,
        ];
    }

    protected function getLinks(Asset $asset): array
    {
        $baseUrl = Craft::parseEnv(
            Craft::$app->getSites()->primarySite->baseUrl
        );

        return  [
            [
                'rel' => 'self',
                'uri' => $baseUrl . '/console/assets/' . $asset->uid,
            ]
        ];
    }
}
