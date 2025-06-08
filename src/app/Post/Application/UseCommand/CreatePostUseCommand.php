<?php

namespace App\Post\Application\UseCommand;

use InvalidArgumentException;

class CreatePostUseCommand
{
    public function __construct(
        private readonly int $userId,
        private readonly string $content,
        private readonly ?string $mediaPath,
        private readonly string $visibility,
    ) {
    }

    private static array $properties = [
        'user_id',
        'content',
        'media_path',
        'visibility',
    ];

    public static function build(array $data): self
    {
        self::validate($data);

        return new self(
            userId: $data['user_id'],
            content: $data['content'],
            mediaPath: $data['media_path'] ?? null,
            visibility: $data['visibility'],
        );
    }

    private static function validate(array $data): void
    {
        foreach (self::$properties as $property) {
            if ($property === 'media_path') {
                continue;
            }

            if (!array_key_exists($property, $data)) {
                throw new InvalidArgumentException("Missing required property: {$property}");
            }
        }
    }
}