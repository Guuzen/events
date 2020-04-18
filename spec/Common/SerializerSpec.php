<?php

namespace spec\App\Common;

use App\Common\Serializer;
use PhpSpec\ObjectBehavior;
use spec\Common\NullObject;
use spec\Common\ObjectWithFooProperty;

class SerializerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Serializer::class);
    }

    function it_is_deserialize_empty_json_object_to_null_object()
    {
        $this
            ->deserialize('{}', NullObject::class)
            ->shouldBeLike(new NullObject())
        ;
    }

    function it_is_deserialize_empty_string_to_null_object()
    {
        $this
            ->deserialize('{"foo": null}', ObjectWithFooProperty::class)
            ->shouldBeLike(new ObjectWithFooProperty(null))
        ;
    }
}
