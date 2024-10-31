import { registerBlockType } from '@wordpress/blocks';
import { TextControl, ResizableBox, PanelBody } from '@wordpress/components';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';

const baseURL = 'https://app.reservation.tools/i/';
const { token } = myBlockData;

registerBlockType('reservation-tools/booking-form', {
    title: 'Reservation.Tools Booking Form',
    icon: 'text-page',
    category: 'widgets',
    attributes: {
        token: {
            type: 'string',
            default: token
        },
        height: {
            type: 'number',
            default: 700
        }
    },
    edit: ({ attributes, setAttributes }) => {
        const { token, height } = attributes;

        const onResizeStop = (event, direction, elt, delta) => {
            setAttributes({
                height: height + delta.height
            });
        };

        return (
            <div {...useBlockProps()}>
                <ResizableBox
                    size={{ height }}
                    enable={{
                        bottom: true,
                        bottomRight: true,
                        right: true,
                    }}
                    onResizeStop={onResizeStop}
                >
                    {!token && <NotConnected />}
                    {token && <iframe src={`${baseURL}${token}`} style={{ width: '100%', height: height + 'px' }} />}
                </ResizableBox>
            </div>
        );
    },
    save: ({ attributes }) => {
        const blockProps = useBlockProps.save();
        const { token, height } = attributes;
        console.log(token);

        return (
            <div {...blockProps}>
                {!token && <NotConnected />}
                {token && <iframe src={`${baseURL}${token}`} style={{ width: '100%', height: height + 'px' }} />}
            </div>
        );
    },
});

const NotConnected = () => (
    <div style={{
        width: '100%',
        height: '100%',
        display: 'flex',
        textAlign: 'center',
        alignItems: 'center',
        background: 'antiquewhite'
    }}> Set your account key in the Admin Dashboard, go to Reservation.Tools Settings Page.</div>
);
