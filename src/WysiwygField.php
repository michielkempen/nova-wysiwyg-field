<?php

namespace Michielkempen\NovaWysiwygField;

use Laravel\Nova\Fields\Deletable;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Contracts\Deletable as DeletableContract;
use Laravel\Nova\Http\Requests\NovaRequest;
use Michielkempen\NovaWysiwygField\Jobs\DeleteWysiwygFiles;
use Michielkempen\NovaWysiwygField\Jobs\DetachWysiwygFile;
use Michielkempen\NovaWysiwygField\Jobs\StorePendingWysiwygFile;
use Michielkempen\NovaWysiwygField\Models\PendingWysiwygFile;
use Webpatser\Uuid\Uuid;

class WysiwygField extends Field implements DeletableContract
{
    use Deletable;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'wysiwyg-field';

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = false;

    /**
     * The disk that should be used to store files.
     *
     * @var string
     */
    public $disk = 'public';

    /**
     * The callback that should be executed to store file attachments.
     *
     * @var callable
     */
    public $attachCallback;

    /**
     * The callback that should be executed to delete persisted file attachments.
     *
     * @var callable
     */
    public $detachCallback;

    /**
     * NovaWysiwygField constructor.
     *
     * @param string $name
     * @param null|string $attribute
     * @param mixed|null $resolveCallback
     * @throws \Exception
     */
    public function __construct(string $name, ?string $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->attachCallback = new StorePendingWysiwygFile($this);
        $this->detachCallback = new DetachWysiwygFile($this);
        $this->deleteCallback = new DeleteWysiwygFiles($this);
        $this->prunable = true;

        $draftId = (string) Uuid::generate(4);

        $this->withMeta([
            'draftId' => $draftId,
        ]);
    }

    /**
     * The disk that should be used to store attachments.
     *
     * @param  string  $disk
     * @return $this
     */
    public function disk($disk)
    {
        $this->disk = $disk;

        return $this;
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param NovaRequest $request
     * @param string $requestAttribute
     * @param object $model
     * @param string $attribute
     * @return void
     */
    protected function fillAttribute(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        parent::fillAttribute($request, $requestAttribute, $model, $attribute);

        if ($request->{$this->attribute.'DraftId'}) {
            PendingWysiwygFile::persistDraft(
                $request->{$this->attribute.'DraftId'},
                $this,
                $model
            );
        }
    }
}
