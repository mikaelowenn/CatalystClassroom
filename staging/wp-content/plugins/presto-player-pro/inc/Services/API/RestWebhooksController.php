<?php

namespace PrestoPlayer\Pro\Services\API;

use PrestoPlayer\Models\Webhook;

class RestWebhooksController extends \WP_REST_Controller
{
    protected $namespace = 'presto-player';
    protected $version = 'v1';
    protected $base = 'webhook';

    /**
     * Register controller
     *
     * @return void
     */
    public function register()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    /**
     * Register presets routes
     *
     * @return void
     */
    public function register_routes()
    {
        register_rest_route("{$this->namespace}/{$this->version}", '/' . $this->base, [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this, 'get_items'],
                'permission_callback' => [$this, 'get_items_permissions_check'],
                'args'                => [],
            ],
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'create_item'],
                'permission_callback' => [$this, 'create_item_permissions_check'],
                'args'                => $this->get_endpoint_args_for_item_schema(true),
            ],
            'schema' => [$this, 'get_preset_schema']
        ]);

        register_rest_route("{$this->namespace}/{$this->version}", '/' . $this->base . '/(?P<id>\d+)', [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'get_item'],
                'permission_callback' => [$this, 'get_item_permissions_check'],
                'args' => [
                    'id' => [
                        'validate_callback' => function ($param) {
                            return is_numeric($param);
                        }
                    ],
                    'context' => [
                        'default' => 'view',
                    ],
                ],
            ],
            [
                'methods'             => \WP_REST_Server::EDITABLE,
                'callback'            => [$this, 'update_item'],
                'permission_callback' => [$this, 'update_item_permissions_check'],
                'args'                => $this->get_endpoint_args_for_item_schema(false),
            ],
            [
                'methods'             => \WP_REST_Server::DELETABLE,
                'callback'            => [$this, 'delete_item'],
                'permission_callback' => [$this, 'delete_item_permissions_check'],
                'args'                => [
                    'force' => [
                        'default' => false,
                    ],
                ],
            ],
            'schema' => [$this, 'get_preset_schema']
        ]);
    }

    public function get_preset_schema()
    {
        return [
            '$schema'              => 'http://json-schema.org/draft-04/schema#',
            'title'                => 'preset',
            'type'                 => 'object',
            'properties'           => [
                'id' => [
                    'description'  => esc_html__('Unique identifier for the object.', 'presto-player'),
                    'type'         => 'integer',
                    'context'      => array('view', 'edit', 'embed'),
                    'readonly'     => true,
                ],
                'name' => [
                    'description'  => esc_html__('Name for the preset.', 'presto-player'),
                    'type' => 'string',
                    'required' => true,
                    'validate_callback' => 'is_string',
                    'sanitize_callback' => 'sanitize_text_field'
                ],
                'url' => [
                    'description'  => esc_html__('The url for the request', 'presto-player'),
                    'type'         => 'string',
                    'required'  => true,
                ],
                'method' => [
                    'description'  => esc_html__('The method to use', 'presto-player'),
                    'type' => 'string',
                    'required' => true,
                    'validate_callback' => 'is_string',
                    'sanitize_callback' => 'sanitize_text_field'
                ],
                'email_name' => [
                    'description'  => esc_html__('The email field name.', 'presto-player'),
                    'type' => 'string',
                    'validate_callback' => 'is_string',
                    'sanitize_callback' => 'sanitize_text_field'
                ],
                'headers' => [
                    'description'  => esc_html__('The request headers', 'presto-player'),
                    'type' => 'array',
                ]
            ],
        ];
    }

    /**
     * Get a collection of items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_items($request)
    {
        $preset = new Webhook();
        $items = $preset->fetch([
            'per_page' => 10000,
            'order_by' => [
                'created_at' => 'ASC',
            ]
        ]);

        if (is_wp_error($items)) {
            return $items;
        }

        if (!isset($items->data)) {
            return new \WP_Error('error', 'Something went wrong');
        }

        $data = [];
        foreach ($items->data as $item) {
            $itemdata = $this->prepare_item_for_response($item, $request);
            $data[] = $this->prepare_response_for_collection($itemdata);
        }

        return new \WP_REST_Response($data, 200);
    }

    /**
     * Get one item from the collection
     *
     * @param \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function get_item($request)
    {
        $item = new Webhook($request['id']);
        $data = $this->prepare_item_for_response($item, $request);
        return new \WP_REST_Response($data, 200);
    }

    /**
     * Create one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function create_item($request)
    {
        $item = $this->prepare_item_for_database($request);

        $preset = new Webhook();
        $preset->create($item);
        $preset->fresh();

        $data = $this->prepare_item_for_response($preset, $request);

        if (is_wp_error($data)) {
            return $data;
        }

        if (!empty($data)) {
            return new \WP_REST_Response($data, 200);
        }

        return new \WP_Error('cant-create', __('Cannot create preset.', 'presto-player'), ['status' => 500]);
    }

    /**
     * Update one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function update_item($request)
    {
        $item = $this->prepare_item_for_database($request);

        $preset = new Webhook($request['id']);
        $preset->update($item);

        $data = $this->prepare_item_for_response($preset, $request);

        if (is_wp_error($data)) {
            return $data;
        }

        if (!empty($data)) {
            return new \WP_REST_Response($data, 200);
        }

        return new \WP_Error('cant-update', __('Cannot update preset.', 'presto-player'), ['status' => 500]);
    }

    /**
     * Delete one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function delete_item($request)
    {
        $preset = new Webhook($request['id']);
        $trashed = $preset->trash();

        if ($trashed) {
            return new \WP_REST_Response(true, 200);
        }

        if (is_wp_error($trashed)) {
            return $trashed;
        }

        return new \WP_Error('cant-trash', __('This preset could not be trashed.', 'presto-player'), ['status' => 500]);
    }

    /**
     * Check if a given request has access to get items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function get_items_permissions_check($request)
    {
        return $this->get_item_permissions_check($request);
    }

    /**
     * Check if a given request has access to get items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function get_item_permissions_check($request)
    {
        return current_user_can('edit_posts');
    }

    /**
     * Check if a given request has access to create items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function create_item_permissions_check($request)
    {
        return $this->get_item_permissions_check($request);
    }

    /**
     * Check if a given request has access to update a specific item
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function update_item_permissions_check($request)
    {
        return $this->create_item_permissions_check($request);
    }

    /**
     * Check if a given request has access to delete a specific item
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function delete_item_permissions_check($request)
    {
        return $this->create_item_permissions_check($request);
    }


    /**
     * Prepare the item for create or update operation
     *
     * @param WP_REST_Request $request Request object
     * @return WP_Error|object $prepared_item
     */
    protected function prepare_item_for_database($request)
    {
        return [
            'name' => sanitize_text_field($request['name']),
            'url' => esc_url($request['url']),
            'method' => sanitize_text_field($request['method']),
            'email_name' => sanitize_text_field($request['email_name']),
            'headers' => (array) $request['headers'],
        ];
    }

    /**
     * Prepare the item for the REST response
     *
     * @param mixed $item WordPress representation of the item.
     * @param WP_REST_Request $request Request object.
     * @return mixed
     */
    public function prepare_item_for_response($item, $request)
    {
        $item = $item->toArray();
        $schema = $this->get_preset_schema();
        $prepared = [];
        foreach ($item as $name => $value) {
            if (!empty($schema['properties'][$name])) {
                $prepared[$name] = rest_sanitize_value_from_schema($value, $schema['properties'][$name], $name);
            }
        }

        return $prepared;
    }

    /**
     * Get the query params for collections
     *
     * @return array
     */
    public function get_collection_params()
    {
        return [
            'page'     => [
                'description'       => 'Current page of the collection.',
                'type'              => 'integer',
                'default'           => 1,
                'sanitize_callback' => 'absint',
            ],
            'per_page' => [
                'description'       => 'Maximum number of items to be returned in result set.',
                'type'              => 'integer',
                'default'           => 10,
                'sanitize_callback' => 'absint',
            ],
            'search'   => [
                'description'       => 'Limit results to those matching a string.',
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ],
        ];
    }
}
