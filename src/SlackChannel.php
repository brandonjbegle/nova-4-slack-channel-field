<?php

namespace BrandonJBegle\SlackChannel;

use Illuminate\Support\Facades\Log;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class SlackChannel extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'slack-channel-field';

    protected function fillAttributeFromRequest(NovaRequest $request,
                                                            $requestAttribute,
                                                            $model,
                                                            $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = json_decode($request->get($requestAttribute));
        }
    }

    public function types($types)
    {
        $this->withMeta([
            'types' => $types
        ]);

        return $this;
    }

}
