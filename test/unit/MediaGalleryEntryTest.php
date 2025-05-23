<?php
declare(strict_types=1);

namespace  SnowIO\Magento2DataModel\Test;

use PHPUnit\Framework\TestCase;
use SnowIO\Magento2DataModel\ExtensionAttribute;
use SnowIO\Magento2DataModel\ExtensionAttributeSet;
use SnowIO\Magento2DataModel\MediaGalleryEntry;
use SnowIO\Magento2DataModel\MediaGalleryEntryContent;

class MediaGalleryEntryTest extends TestCase
{
    public function testToJson()
    {
        $mediaGallery = MediaGalleryEntry::of('image', 'Label')
            ->withFile('path/image.jpg')
            ->withContent(MediaGalleryEntryContent::of('type', 'name', 'encoded'))
            ->withTypes(['image', 'small_image', 'thumbnail']);

        $this->assertEquals([
                'media_type' => 'image',
                'label' => 'Label',
                'position' => 0,
                'disabled' => false,
                'content' => [
                    'type' => 'type',
                    'name' => 'name',
                    'base64_encoded_data' => 'encoded',
                ],
                'file' => 'path/image.jpg',
                'types' => ['image', 'small_image', 'thumbnail']
        ], $mediaGallery->toJson());
    }

    public function testFromJson()
    {
        $mediaGallery = MediaGalleryEntry::fromJson([
            'media_type' => 'image',
            'label' => 'Label',
            'position' => 0,
            'disabled' => false,
            'content' => [
                'base64_encoded_data' => base64_encode('test'),
                'type' => 'image/png',
                'name' => 'test',
            ],
            'file' => 'path/image.jpg',
            'types' => ['image', 'small_image', 'thumbnail']
        ]);

        $this->assertEquals([
            'media_type' => 'image',
            'label' => 'Label',
            'position' => 0,
            'disabled' => false,
            'content' => [
                'base64_encoded_data' => base64_encode('test'),
                'type' => 'image/png',
                'name' => 'test',
            ],
            'file' => 'path/image.jpg',
            'types' => ['image', 'small_image', 'thumbnail']
        ], $mediaGallery->toJson());
    }

    public function testAccessors()
    {
        $mediaGallery = MediaGalleryEntry::of('image', 'Label');

        $this->assertEquals('image', $mediaGallery->getMediaType());
        $this->assertEquals('Label', $mediaGallery->getLabel());
        $this->assertEquals(0, $mediaGallery->getDisabled());
        $this->assertEquals(0, $mediaGallery->getPosition());
        $this->assertEquals(null, $mediaGallery->getFile());
        $this->assertEquals([], $mediaGallery->getTypes());

        $mediaGallery = MediaGalleryEntry::of('image', 'Label')
            ->withFile('path/image.jpg')
            ->withTypes(['image', 'small_image', 'thumbnail'])
            ->withDisabled(true)
            ->withPosition(1)
            ->withExtensionAttributes(
                ExtensionAttributeSet::of([
                    ExtensionAttribute::of('video_content', [
                        "media_type" => 'external-video',
                        "video_provider" => 'youtube',
                        "video_url" => 'https://www.youtube.com/watch?v=fake',
                        "video_title" => 'Video title',
                        "video_description" => 'Video description',
                        "video_metadata" => '',
                    ])
                ])
            );

        $this->assertEquals(1, $mediaGallery->getDisabled());
        $this->assertEquals(1, $mediaGallery->getPosition());
        $this->assertEquals(['video_content'=> [
            'media_type' => 'external-video',
            'video_provider' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=fake',
            'video_title' => 'Video title',
            'video_description' => 'Video description',
            'video_metadata' => '',
        ]], $mediaGallery->getExtensionAttributes()->toJson());
        $this->assertEquals('path/image.jpg', $mediaGallery->getFile());
        $this->assertEquals(['image', 'small_image', 'thumbnail'], $mediaGallery->getTypes());
    }

    public function testEquals()
    {
        $mediaGallery = MediaGalleryEntry::fromJson([
            'media_type' => 'image',
            'label' => 'Label',
            'position' => 0,
            'disabled' => false,
            'content' => [
                'type' => 'type',
                'name' => 'name',
                'base64_encoded_data' => 'encoded',
            ],
            'file' => 'path/image.jpg',
            'types' => ['image', 'small_image', 'thumbnail'],
            'extension_attributes' => [
                'test' => 1
            ],
        ]);

        $mediaGallery2 = MediaGalleryEntry::of('image', 'Label')
            ->withFile('path/image.jpg')
            ->withTypes(['image', 'small_image', 'thumbnail'])
            ->withDisabled(false)
            ->withPosition(0)
            ->withContent(MediaGalleryEntryContent::of('type', 'name', 'encoded'))
            ->withExtensionAttributes(
                ExtensionAttributeSet::of([
                    ExtensionAttribute::of('test', 1)
                ])
            );

        $this->assertTrue($mediaGallery->equals($mediaGallery2));
    }

    public function testEmptyCustomAttribute()
    {
        $mediaGallery = MediaGalleryEntry::of('image', 'Label')
            ->withFile('path/image.jpg')
            ->withExtensionAttributes(
                ExtensionAttributeSet::of([])
            );

        $this->assertEquals([
            'media_type' => 'image',
            'label' => 'Label',
            'position' => 0,
            'disabled' => false,
            'types' => [],
            'file' => 'path/image.jpg',
        ], $mediaGallery->toJson());

        $mediaGallery = MediaGalleryEntry::of('image', 'Label')
            ->withFile('path/image.jpg')
            ->withExtensionAttributes(
                ExtensionAttributeSet::of([
                    ExtensionAttribute::of('test', 1)
                ])
            );

        $this->assertEquals([
            'media_type' => 'image',
            'label' => 'Label',
            'position' => 0,
            'disabled' => false,
            'types' => [],
            'file' => 'path/image.jpg',
            'extension_attributes' => [
                'test' => 1
            ],
        ], $mediaGallery->toJson());
    }

    public function testSimpleCustomAttribute()
    {
        $mediaGallery = MediaGalleryEntry::of('image', 'Label')
            ->withFile('path/image.jpg')
            ->withExtensionAttributes(
                ExtensionAttributeSet::of([
                    ExtensionAttribute::of('test', 1)
                ])
            );

        $this->assertEquals([
            'media_type' => 'image',
            'label' => 'Label',
            'position' => 0,
            'disabled' => false,
            'types' => [],
            'file' => 'path/image.jpg',
            'extension_attributes' => [
                'test' => 1
            ],
        ], $mediaGallery->toJson());
    }
}
