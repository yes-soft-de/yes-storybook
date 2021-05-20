import { ReactiveFormsModule } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { Meta, Story } from '@storybook/angular';
import { moduleMetadata } from '@storybook/angular';
import { InputComponent } from '../app/shared/ui/component/input/input.component';

export default {
    title: 'Shared/Input',
    component: InputComponent,
    decorators: [
        moduleMetadata({
            imports: [
                ReactiveFormsModule,
                MatIconModule,
                MatFormFieldModule,
                MatInputModule,
                BrowserAnimationsModule
            ],
            declarations: [InputComponent],
        })
    ],
    argTypes: {
        placeholder: {
            control: 'text'
        },
        label: {
            control: 'text'
        }
    }
} as Meta;

const Template: Story<InputComponent> = (args: InputComponent) => ({
    component: InputComponent,
    props: args
});

export const Flat: Story<InputComponent> = Template.bind({});
Flat.args = {
    containerClassName: 'flat text-textMedium',
    placeholder: 'Search',
    label: 'Label'
};
