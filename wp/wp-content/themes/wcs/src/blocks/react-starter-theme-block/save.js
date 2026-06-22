import { useBlockProps } from '@wordpress/block-editor';

export default function save() {
    const blockProps = useBlockProps.save();
    return (
        <div {...blockProps}>
            <p>Hello from the frontend!</p>
        </div>
    );
}
